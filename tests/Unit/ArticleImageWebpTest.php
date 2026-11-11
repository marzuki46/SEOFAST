<?php

namespace Tests\Unit;

use App\Services\ImageService;
use Tests\TestCase;

class ArticleImageWebpTest extends TestCase
{
    public function test_optimize_under_limit_compresses_and_converts_to_webp(): void
    {
        $service = new ImageService;

        // Generate high-resolution test image
        $tmpSource = tempnam(sys_get_temp_dir(), 'test_src_');
        $img = imagecreatetruecolor(1600, 1200);
        for ($x = 0; $x < 1600; $x += 40) {
            for ($y = 0; $y < 1200; $y += 40) {
                $color = imagecolorallocate($img, ($x * 3) % 255, ($y * 5) % 255, ($x + $y) % 255);
                imagefilledrectangle($img, $x, $y, $x + 40, $y + 40, $color);
            }
        }
        imagepng($img, $tmpSource);
        imagedestroy($img);

        $destWebp = sys_get_temp_dir().DIRECTORY_SEPARATOR.'test_out_'.uniqid().'.webp';

        try {
            $result = $service->optimizeUnderLimit($tmpSource, $destWebp, 'image/png', 100000);

            $this->assertFileExists($destWebp);
            $this->assertLessThanOrEqual(100000, $result['file_size']);
            $this->assertSame('image/webp', $result['mime_type']);

            $info = @getimagesize($destWebp);
            $this->assertSame('image/webp', $info['mime']);
        } finally {
            @unlink($tmpSource);
            @unlink($destWebp);
        }
    }
}
