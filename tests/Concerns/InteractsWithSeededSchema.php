<?php

namespace Tests\Concerns;

use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * Trait untuk test yang butuh tabel oauth_*, users, employees, dll.
 *
 * CATATAN: trait ini sengaja TIDAK memakai RefreshDatabase, karena migrasi
 * project ini tidak bisa dijalankan pada database kosong. Contohnya
 * `2024_09_13_135148_add_location_to_employees_table` memakai
 * `after('atasan_id')`, padahal kolom `atasan_id` baru ada di migrasi yang
 * lebih baru. Ini warisan project, bukan masalah Passport.
 *
 * Sebagai gantinya, database `empapps_test` (lihat phpunit.xml) disiapkan
 * satu kali dengan menyalin SKEMA dari database development tanpa datanya,
 * lalu setiap test dibungkus transaksi yang otomatis di-rollback. Dengan
 * begitu test tidak pernah menyentuh data asli.
 *
 * Persiapan sekali jalan (butuh akses ke MySQL dev):
 *   CREATE DATABASE empapps_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
 *   mysqldump --no-data --routines --triggers <db_dev> | mysql empapps_test
 */
trait InteractsWithSeededSchema
{
    use DatabaseTransactions;
}
