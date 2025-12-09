<?php

function actionButtons($id, $routeBase)
{
    $btn = '';

    // VIEW
    if (hasPermission($routeBase.'.show')) {
        $btn .= '<a href="'.route($routeBase.'.show', $id).'" 
                    class="btn btn-sm btn-primary me-2">
                    <i class="fas fa-eye"></i>
                 </a> &nbsp;';
    }

    // EDIT
    if (hasPermission($routeBase.'.edit')) {
        $btn .= '<a href="'.route($routeBase.'.edit', $id).'" 
                    class="btn btn-sm btn-warning me-2">
                    <i class="fas fa-edit"></i>
                 </a> &nbsp;';
    }

    // DELETE
    if (hasPermission($routeBase.'.destroy')) {
        $btn .= '<button class="btn btn-sm btn-danger" 
                    onclick="deleteData(\''.$id.'\')">
                    <i class="fas fa-trash"></i>
                 </button>';
    }

    return $btn;
}
