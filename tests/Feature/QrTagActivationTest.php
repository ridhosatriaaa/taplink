<?php

namespace Tests\Feature;

use App\Models\QrTag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class QrTagActivationTest extends TestCase
{
    use RefreshDatabase;

    public function test_qr_tag_activation_accepts_pin_and_hashes_it(): void
    {
        $qrTag = QrTag::create([
            'id' => '001',
            'is_active' => false,
        ]);

        $response = $this->post(route('qr.activate', $qrTag->id), [
            'place_id' => 'ChIJN1t_tDeuEmsR',
            'whatsapp_number' => '628123456789',
            'activation_pin' => '1234',
        ]);

        $response->assertRedirect(route('qr.scan', ['qrTag' => $qrTag->id, 'status' => 'sukses']));

        $qrTag->refresh();

        $this->assertTrue((bool) $qrTag->is_active);
        $this->assertSame('1234', $qrTag->activation_pin);
        $this->assertNotNull($qrTag->activation_pin);
    }
}
