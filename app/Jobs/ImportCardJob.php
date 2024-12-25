<?php

namespace App\Jobs;

use App\Repositories\TemplateRepository;
use App\Services\HtmlToImageService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\UploadedFile;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;
use voku\helper\HtmlDomParser;

class ImportCardJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $filePath;
    private $content;
    private $userId;

    public function __construct(string $filePath, string $content, int $userId  )
    {
        $this->filePath = $filePath;
        $this->content = $content;
        $this->userId = $userId;
    }

    public function handle()
    {
        $htmlCssToImageService = app(HtmlToImageService::class);
        $templateRepository = resolve(TemplateRepository::class);

        try {
            $csv = Reader::createFromPath(storage_path('app/' . $this->filePath), 'r');
            $csv->setHeaderOffset(0);
            $records = $csv->getRecords();

            DB::beginTransaction();

            foreach ($records as $record) {
                $updatedContent = $this->replaceImageInContent($this->content, $record['image']);
                $processedContent = str_replace(
                    ['_Name_', '_Company_', '_Greeting_message_'],
                    [$record['name'], $record['company'], $record['greeting_message']],
                    $updatedContent
                );
                $imageUrl = $htmlCssToImageService->generateImage($processedContent);
                $templateRepository->create([
                    'content' => $processedContent,
                    'image' => $imageUrl,
                    'user_id' => $this->userId,
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            \Log::error('Import process failed: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            DB::rollBack();
        } finally {
            @unlink(storage_path('app/' . $this->filePath));
        }
    }

    private function replaceImageInContent(string $content, string $imageUrl): string
    {
        $dom = HtmlDomParser::str_get_html($content);
        
        $imgElement = $dom->findOne('img.image-special');
        if ($imgElement) {
            $imgElement->setAttribute('src', $imageUrl);
        }

        return $dom->save();
    }

    /**
     * Convert image file to base64 string with proper mime type
     *
     * @param UploadedFile $image
     * @return string
     */
    private function convertImageToBase64(UploadedFile $image): string
    {
        return sprintf(
            'data:%s;base64,%s',
            $image->getMimeType(),
            base64_encode(file_get_contents($image->getPathname()))
        );
    }
}
