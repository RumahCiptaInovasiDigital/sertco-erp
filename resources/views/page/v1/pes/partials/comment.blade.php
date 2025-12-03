<div class="timeline">  
    @if ($comments->count() == 0)
    <div>
        <div class="timeline-item">
            <div class="timeline-body">
                <p class="text-muted">Belum ada Komentar untuk Project ini.</p>
            </div>
        </div>
    </div>
    @endif
    @foreach($comments as $comment)
        <div>
            <div class="timeline-item">
                <span class="time"><i class="fas fa-clock"></i> {{ $comment->created_at->diffForHumans() }}</span>
                <h3 class="timeline-header">{{ $comment->user->fullName ?? 'unknown user' }} - {{ $comment->user->jabatan->hasDepartemen->departemen->name ?? 'unknown department' }}</h3>

                <div class="timeline-body">
                    <p>{!! nl2br(e($comment->comment)) !!}</p>

                    @if($comment->image_path)
                        <div class="mt-2">
                            <a href="{{ asset('storage/'.$comment->image_path) }}" target="_blank">
                                <img src="{{ asset('storage/'.$comment->image_path) }}" class="img-fluid rounded" style="max-width:320px;">
                            </a>
                        </div>
                    @endif
                </div>
                <div class="timeline-footer d-flex">
                    <button type="button" 
                            class="btn btn-sm btn-default like-btn mr-2" 
                            data-id="{{ $comment->id }}">
                        <i class="fas fa-thumbs-up"></i>
                        <span class="like-count">({{ $comment->likes()->count() }})</span>
                    </button>
                
                    <button type="button" 
                            class="btn btn-sm btn-primary reply-toggle" 
                            data-id="{{ $comment->id }}">
                        Reply
                    </button>
                </div>
                
                <div class="reply-form mx-2 pb-2 d-none" id="reply-form-{{ $comment->id }}">
                    <div class="form-group">
                        <textarea class="form-control reply-text" rows="2" placeholder="Write a reply..."></textarea>
                    </div>
                    <div class="form-group d-flex align-items-center mx-2">
                        <input type="file" class="form-control" accept="image/*">
                        <button class="btn btn-sm btn-primary ml-2 send-reply" data-id="{{ $comment->id }}">Send</button>
                    </div>
                </div>
            </div>
        </div>
        
        @php
            $children = $comment->nestedReplies();
        @endphp

        @if($children->count())
            <div class="ml-md-4 mt-3 pl-3">
                @include('page.v1.pes.partials.comment', ['comments' => $children])
            </div>
        @endif
    @endforeach
</div>  