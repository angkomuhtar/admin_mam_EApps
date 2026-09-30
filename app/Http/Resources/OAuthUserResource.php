<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Representasi user yang dikirim ke aplikasi OAuth2 client (plant).
 *
 * Sengaja dibuat terpisah dari UserResource yang sudah ada: UserResource
 * dipakai oleh API lama (JWT/mobile) dan isinya tidak boleh berubah.
 */
class OAuthUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            // `name` = nama lengkap (dari tabel profiles).
            // Kalau user belum punya profil, fallback ke username login.
            'name' => $this->profile?->name ?? $this->username,

            // `username` = username login (dari tabel users).
            'username' => $this->username,

            'email' => $this->email,

            // `job_title` = nama jabatan (dari tabel positions).
            'job_title' => $this->employee?->position?->position,

            // `position_id` = id jabatan mentah (dari tabel employees).
            'position_id' => $this->employee?->position_id,

            // Kolom `status` di tabel users bernilai 'Y' / 'N'.
            'is_active' => $this->status === 'Y',
        ];
    }
}
