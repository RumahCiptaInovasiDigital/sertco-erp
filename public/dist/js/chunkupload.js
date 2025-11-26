(function () {
    const CHUNK_SIZE = 5 * 1024 * 1024; // 5MB, ubah sesuai kebutuhan
    const CONCURRENCY = 3; // parallel per-file

    $("#uploadForm").on("submit", function (e) {
        e.preventDefault();
        const form = this;

        const priceFile = document.getElementById("priceDoc").files[0] || null;
        const unpriceFile =
            document.getElementById("unpriceDoc").files[0] || null;

        const tasks = [];
        if (priceFile) tasks.push({ file: priceFile, fieldName: "priceDoc" });
        if (unpriceFile)
            tasks.push({ file: unpriceFile, fieldName: "unpriceDoc" });

        var formData = new FormData(this);

        // jika tidak ada file, submit langsung
        if (!tasks.length) {
            submitForm(form, formData);
            return;
        }

        // tampilkan swal dengan area progress
        Swal.fire({
            title: "Uploading files...",
            html: `
                <div id="sw-files" style="text-align:left;"></div>
                <hr>
                <div style="text-align:left;">
                    <div style="background:#eee;height:10px;border-radius:6px;">
                        <div id="totalBar" style="width:0%;height:10px;background:#3085d6;border-radius:6px;"></div>
                    </div>
                    <small id="totalPercent">0%</small><br>
                    <small id="totalSpeed">Speed: 0 MB/s</small><br>
                    <small id="totalEta">ETA: calculating...</small>
                </div>
            `,
            allowOutsideClick: false,
            allowEscapeKey: false,
            showCancelButton: true,
            cancelButtonText: "Cancel All",
            didOpen: () => {
                startUploads(tasks, form, formData);
            },
        }).then((res) => {});
    });

    async function startUploads(taskFiles, form, formData) {
        const fileStates = [];
        const controllers = {}; // { uploadId: { aborted: bool, xhr: [xhr,...] } }

        // render UI for each file
        const swFiles = document.getElementById("sw-files");
        swFiles.innerHTML = "";
        taskFiles.forEach((t, idx) => {
            const id = `file-${idx}`;
            swFiles.insertAdjacentHTML(
                "beforeend",
                `
                <div id="${id}" style="text-align:left;margin-bottom:12px;">
                    <b>${t.file.name}</b> (${(
                    t.file.size /
                    1024 /
                    1024
                ).toFixed(2)} MB)
                    <div style="margin:6px 0;">
                        <div style="background:#ddd;width:100%;height:8px;border-radius:4px;">
                            <div class="bar" style="height:8px;width:0%;background:#17a2b8;border-radius:4px;"></div>
                        </div>
                    </div>
                    <small class="speed">Speed: 0 MB/s</small> · <small class="eta">ETA: calculating...</small>
                    <button class="cancel-file" data-idx="${idx}" style="margin-left:8px;background:#d33;color:#fff;border:none;padding:4px 8px;border-radius:4px;">Cancel</button>
                </div>
            `
            );

            const uploadId =
                Date.now() + "-" + Math.random().toString(36).substr(2, 9);
            fileStates.push({
                idx,
                id,
                file: t.file,
                fieldName: t.fieldName,
                uploadId,
                totalChunks: Math.ceil(t.file.size / CHUNK_SIZE),
                uploadedBytes: 0,
                finished: false,
                tmp_path: null,
            });
            controllers[uploadId] = { aborted: false, xhr: [] };
        });

        // cancel all
        document
            .querySelector(".swal2-cancel")
            ?.addEventListener("click", () => {
                Object.values(controllers).forEach((c) => {
                    c.aborted = true;
                    c.xhr.forEach((x) => {
                        try {
                            x.abort();
                        } catch (e) {}
                    });
                });
                Swal.close();
            });

        // cancel per file
        document.getElementById("sw-files").addEventListener("click", (ev) => {
            if (ev.target && ev.target.classList.contains("cancel-file")) {
                const idx = parseInt(ev.target.getAttribute("data-idx"));
                const st = fileStates[idx];
                const ctrl = controllers[st.uploadId];
                ctrl.aborted = true;
                ctrl.xhr.forEach((x) => {
                    try {
                        x.abort();
                    } catch (e) {}
                });
                const box = document.getElementById(st.id);
                box.querySelector(".bar").style.background = "#d33";
                box.querySelector(".eta").textContent = "Canceled";
            }
        });

        // helper total UI
        const totalSize = fileStates.reduce((s, f) => s + f.file.size, 0);
        function updateTotalUI() {
            const uploaded = fileStates.reduce(
                (s, st) => s + st.uploadedBytes,
                0
            );
            const percent = totalSize ? (uploaded / totalSize) * 100 : 0;
            document.getElementById("totalBar").style.width = percent + "%";
            document.getElementById("totalPercent").textContent =
                percent.toFixed(1) + "%";

            const now = Date.now();
            const earliestStart = Math.min(
                ...fileStates.map((s) => s._startTime || now)
            );
            const elapsed = (now - earliestStart) / 1000 || 1;
            const speed = uploaded / elapsed;
            document.getElementById("totalSpeed").textContent = `Speed: ${(
                speed /
                1024 /
                1024
            ).toFixed(2)} MB/s`;

            const remaining = totalSize - uploaded;
            const eta = speed > 0 ? remaining / speed : Infinity;
            document.getElementById("totalEta").textContent = isFinite(eta)
                ? `ETA: ${Math.ceil(eta)} s`
                : "ETA: calculating...";
        }

        // upload per file
        async function uploadFile(state) {
            const file = state.file;
            const totalChunks = state.totalChunks;
            const uploadId = state.uploadId;
            const fieldName = state.fieldName;
            const ctrl = controllers[uploadId];

            // queue chunk indexes
            const indexes = Array.from({ length: totalChunks }, (_, i) => i);

            // send one chunk
            function sendChunk(index) {
                return new Promise((resolve, reject) => {
                    if (ctrl.aborted) return reject(new Error("aborted"));

                    const start = index * CHUNK_SIZE;
                    const end = Math.min(start + CHUNK_SIZE, file.size);
                    const blob = file.slice(start, end);

                    const fd = new FormData();
                    fd.append("uploadId", uploadId);
                    fd.append("fieldName", fieldName);
                    fd.append("index", index);
                    fd.append("chunk", blob);

                    const xhr = new XMLHttpRequest();
                    xhr.open("POST", '/chunk-upload', true);
                    xhr.responseType = "json";
                    xhr.setRequestHeader(
                        "X-CSRF-TOKEN",
                        document.querySelector("input[name=_token]").value
                    );

                    // track xhr for cancel
                    ctrl.xhr.push(xhr);

                    xhr.upload.onprogress = function (e) {
                        if (!e.lengthComputable) return;
                        // uploaded absolute = index*CHUNK + e.loaded
                        const loadedAbsolute = index * CHUNK_SIZE + e.loaded;
                        state.uploadedBytes = Math.min(
                            loadedAbsolute,
                            file.size
                        );

                        // per-file UI
                        const box = document.getElementById(state.id);
                        const percent = (state.uploadedBytes / file.size) * 100;
                        box.querySelector(".bar").style.width = percent + "%";

                        // speed & eta per file
                        const now = Date.now();
                        if (!state._startTime) state._startTime = now;
                        const elapsed = (now - state._startTime) / 1000;
                        const sp = state.uploadedBytes / Math.max(1, elapsed);
                        box.querySelector(".speed").textContent = `Speed: ${(
                            sp /
                            1024 /
                            1024
                        ).toFixed(2)} MB/s`;
                        const remaining = file.size - state.uploadedBytes;
                        const eta = sp > 0 ? remaining / sp : Infinity;
                        box.querySelector(".eta").textContent = isFinite(eta)
                            ? `ETA: ${Math.ceil(eta)} s`
                            : "ETA: calculating...";

                        // update total UI
                        updateTotalUI();
                    };

                    xhr.onload = function () {
                        if (xhr.status >= 200 && xhr.status < 300) {
                            resolve(xhr.response);
                        } else {
                            reject(new Error("upload-failed"));
                        }
                    };
                    xhr.onerror = function () {
                        reject(new Error("network-error"));
                    };

                    xhr.send(fd);
                });
            }

            // concurrency workers
            const worker = async () => {
                while (indexes.length && !ctrl.aborted) {
                    const idx = indexes.shift();
                    try {
                        await sendChunk(idx);
                    } catch (err) {
                        if (ctrl.aborted) break;
                        throw err;
                    }
                }
            };

            const workers = Array.from(
                { length: Math.min(CONCURRENCY, totalChunks) },
                () => worker()
            );
            await Promise.all(workers);

            if (ctrl.aborted) throw new Error("aborted");

            // all chunks done -> call complete endpoint
            const fdComplete = new FormData();
            fdComplete.append("uploadId", uploadId);
            fdComplete.append("fieldName", fieldName);
            fdComplete.append("fileName", file.name);
            fdComplete.append("totalChunks", totalChunks);

            const res = await fetch('/chunk-complete', {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN":
                        document.querySelector("input[name=_token]").value,
                },
                body: fdComplete,
            });

            if (!res.ok) throw new Error("complete-failed");
            const json = await res.json();

            state.tmp_path = json.tmp_path; // e.g. tmp/xxxx.pdf
            state.tmp_filename = json.tmp_filename;
            state.finished = true;

            // update UI final state
            const box = document.getElementById(state.id);
            box.querySelector(".bar").style.background = "#28a745";
            box.querySelector(".eta").textContent = "Completed";
            box.querySelector(".speed").textContent = "";
        }

        // run all file uploads in parallel
        try {
            await Promise.all(fileStates.map((st) => uploadFile(st)));
        } catch (err) {
            Swal.close();
            console.error(err);
            Swal.fire({ icon: "error", title: "Upload failed or canceled" });
            return;
        }

        fileStates.forEach((st) => {
            if (st.tmp_path) {
                const input = document.createElement("input");
                input.type = "hidden";
                input.name = st.fieldName + "_tmp"; // e.g. priceDoc_tmp
                input.value = st.tmp_path; // e.g. tmp/abc.pdf
                form.appendChild(input);
            }
        });

        Swal.close();
        // submit form setelah semua file terupload
        formData.delete("priceDoc");
        formData.delete("unpriceDoc");

        fileStates.forEach((st) => {
            if (st.tmp_path) {
                formData.append(st.fieldName + "_tmp", st.tmp_path);
            }
        });

        submitForm(form, formData);
    }
})();

function submitForm(form, formData) {
    $.ajax({
        type: $(form).attr("method"), // Method form POST atau GET
        url: $(form).attr("action"), // URL tujuan
        data: formData, // Gunakan FormData
        processData: false, // Jangan memproses data
        contentType: false, // Jangan set content type
        beforeSend: function () {
            showLoading();
            $(form).find('button[type="submit"]').attr("disabled", true);
            $(form).find('button[type="submit"]').text("Loading...");
        },
        success: function (response) {
            // Proses selesai, enable kembali tombol
            $(form).find('button[type="submit"]').attr("disabled", false);
            $(form).find('button[type="submit"]').text("Submit");
            // console.log(response);

            // Opsional: tangani respons dari Laravel
            if (response.success) {
                // Menampilkan SweetAlert dengan pesan sukses
                Swal.fire({
                    title: "Success :)",
                    text: response.message,
                    icon: "success",
                    allowOutsideClick: false, // Tidak bisa ditutup dengan klik luar
                    allowEscapeKey: false, // Tidak bisa ditutup dengan tombol escape
                    timer: 3000, // Timer 3 detik sebelum redirect
                    timerProgressBar: true, // Progress bar di bawah modal
                    didOpen: () => {
                        Swal.showLoading(); // Menampilkan loading di dalam modal
                    },
                    willClose: () => {
                        // Redirect ke halaman setelah timer selesai
                        window.location.href = response.redirect;
                    },
                });
            } else {
                // Menampilkan SweetAlert dengan pesan error
                Swal.fire({
                    title: "Error System",
                    text: response.message,
                    icon: "error",
                    allowOutsideClick: false, // Tidak bisa ditutup dengan klik luar
                    allowEscapeKey: false, // Tidak bisa ditutup dengan tombol escape
                });

                // Enable kembali tombol submit
                $(form)
                    .find('button[type="submit"]')
                    .attr("disabled", false)
                    .text("Submit");
            }
        },
        error: function (xhr) {
            // Enable kembali tombol submit
            $(form)
                .find('button[type="submit"]')
                .attr("disabled", false)
                .text("Submit");

            // Tangani error validasi dari Laravel
            if (xhr.status === 422) {
                var errors = xhr.responseJSON.errors;

                // Hapus pesan error sebelumnya
                $(".invalid-feedback").remove();
                $(".is-invalid").removeClass("is-invalid");

                var firstErrorField; // Variabel untuk menyimpan elemen error pertama

                // Tampilkan pesan error
                $.each(errors, function (key, value) {
                    var inputField = $(form).find(`[name="${key}"]`);
                    inputField.addClass("is-invalid");
                    inputField.after(
                        `<span class="invalid-feedback" role="alert"><strong>${value[0]}</strong></span>`
                    );

                    // Simpan elemen error pertama
                    if (!firstErrorField) {
                        firstErrorField = inputField;
                    }
                });

                // Scroll ke elemen error pertama
                if (firstErrorField) {
                    $("html, body").animate(
                        {
                            scrollTop: firstErrorField.offset().top - 100, // Offset agar tidak terlalu menempel di atas
                        },
                        "slow"
                    );
                }
            } else {
                alert("Terjadi kesalahan, coba lagi.");
            }
        },
        complete: function () {
            hideLoading();
            $(form).find('button[type="submit"]').attr("disabled", false);
        },
    });
}
