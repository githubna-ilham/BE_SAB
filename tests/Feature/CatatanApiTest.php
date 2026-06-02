<?php

namespace Tests\Feature;

use App\Models\Catatan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatatanApiTest extends TestCase
{
    use RefreshDatabase;

    private array $headers = ['X-API-Key' => 'test-key', 'Accept' => 'application/json'];

    // ===== Index =====

    public function test_index_returns_all_catatan(): void
    {
        Catatan::create(['judul' => 'A', 'isi' => 'isi A', 'kategori' => 'Kuliah', 'dibuat_pada' => now()]);
        Catatan::create(['judul' => 'B', 'isi' => 'isi B', 'kategori' => 'Tugas',  'dibuat_pada' => now()]);

        $res = $this->withHeaders($this->headers)->getJson('/api/catatan');

        $res->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonCount(2, 'data')
            ->assertJsonStructure(['success', 'data' => [['id', 'judul', 'isi', 'kategori', 'dibuat_pada']]]);
    }

    public function test_index_requires_api_key(): void
    {
        $this->getJson('/api/catatan')->assertStatus(401)->assertJson(['success' => false]);
    }

    public function test_index_rejects_wrong_api_key(): void
    {
        $this->withHeaders(['X-API-Key' => 'wrong'])
            ->getJson('/api/catatan')
            ->assertStatus(401);
    }

    // ===== Show =====

    public function test_show_returns_single_catatan(): void
    {
        $c = Catatan::create(['judul' => 'X', 'isi' => 'isi X', 'kategori' => 'Pribadi', 'dibuat_pada' => now()]);

        $this->withHeaders($this->headers)
            ->getJson("/api/catatan/{$c->id}")
            ->assertStatus(200)
            ->assertJson(['success' => true, 'data' => ['id' => $c->id, 'judul' => 'X']]);
    }

    public function test_show_returns_404_when_missing(): void
    {
        $this->withHeaders($this->headers)
            ->getJson('/api/catatan/9999')
            ->assertStatus(404)
            ->assertJson(['success' => false]);
    }

    // ===== Store =====

    public function test_store_creates_catatan(): void
    {
        $res = $this->withHeaders($this->headers)->postJson('/api/catatan', [
            'judul' => 'Baru', 'isi' => 'Isi baru', 'kategori' => 'Kuliah',
        ]);

        $res->assertStatus(201)
            ->assertJson(['success' => true, 'data' => ['judul' => 'Baru']]);
        $this->assertDatabaseHas('catatan', ['judul' => 'Baru']);
    }

    public function test_store_validates_required_fields(): void
    {
        $this->withHeaders($this->headers)
            ->postJson('/api/catatan', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['judul', 'isi', 'kategori']);
    }

    // ===== Update =====

    public function test_update_modifies_catatan(): void
    {
        $c = Catatan::create(['judul' => 'Lama', 'isi' => 'isi', 'kategori' => 'Kuliah', 'dibuat_pada' => now()]);

        $this->withHeaders($this->headers)
            ->putJson("/api/catatan/{$c->id}", ['judul' => 'Baru', 'isi' => 'isi baru', 'kategori' => 'Tugas'])
            ->assertStatus(200)
            ->assertJson(['success' => true, 'data' => ['judul' => 'Baru', 'kategori' => 'Tugas']]);
        $this->assertDatabaseHas('catatan', ['id' => $c->id, 'judul' => 'Baru']);
    }

    public function test_update_returns_404_when_missing(): void
    {
        $this->withHeaders($this->headers)
            ->putJson('/api/catatan/9999', ['judul' => 'X', 'isi' => 'y', 'kategori' => 'Pribadi'])
            ->assertStatus(404);
    }

    // ===== Destroy =====

    public function test_destroy_removes_catatan(): void
    {
        $c = Catatan::create(['judul' => 'X', 'isi' => 'y', 'kategori' => 'Pribadi', 'dibuat_pada' => now()]);

        $this->withHeaders($this->headers)
            ->deleteJson("/api/catatan/{$c->id}")
            ->assertStatus(200)
            ->assertJson(['success' => true]);
        $this->assertDatabaseMissing('catatan', ['id' => $c->id]);
    }

    public function test_destroy_returns_404_when_missing(): void
    {
        $this->withHeaders($this->headers)
            ->deleteJson('/api/catatan/9999')
            ->assertStatus(404);
    }
}
