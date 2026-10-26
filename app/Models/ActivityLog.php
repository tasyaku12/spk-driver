<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'user_name',
        'role',
        'action',
        'module',
        'description',
        'ip_address',
        'user_agent',
        'properties',
        'created_at',
    ];

    protected $casts = [
        'properties' => 'array',
        'created_at' => 'datetime',
    ];

    // Log aktivitas sekarang dapat dihapus oleh admin yang berwenang

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Catat aktivitas ke database secara instan
     */
    public static function record(
        string $action,
        string $module,
        string $description,
        ?array $properties = null,
        ?User $user = null
    ): self {
        $currentUser = $user ?? (auth()->check() ? auth()->user() : null);
        $req = request();

        return static::create([
            'user_id'     => $currentUser?->id,
            'user_name'   => $currentUser?->name ?? 'Sistem / Anonim',
            'role'        => $currentUser?->role ?? 'system',
            'action'      => strtoupper(trim($action)),
            'module'      => trim($module),
            'description' => trim($description),
            'ip_address'  => $req ? $req->ip() : null,
            'user_agent'  => $req ? substr((string) $req->userAgent(), 0, 500) : null,
            'properties'  => $properties,
            'created_at'  => now(),
        ]);
    }
}
