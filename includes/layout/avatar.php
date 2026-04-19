<?php
function getUserAvatar($user) {
    $name = $user['name'] ?? 'User';
    $photo = $user['profile_photo'] ?? null;

    $names = explode(' ', trim($name));
    $initials = strtoupper(substr($names[0], 0, 1) . substr(end($names), 0, 1));

    if ($photo) {
        return [
            'type' => 'image',
            'value' => "/enrollmentSystem/uploads/avatars/" . $photo
        ];
    }

    return [
        'type' => 'initials',
        'value' => $initials
    ];
}