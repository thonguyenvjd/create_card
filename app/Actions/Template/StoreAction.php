<?php

namespace App\Actions\Template;

use App\Jobs\ImportCardJob;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use League\Csv\Reader;

class StoreAction extends BaseAction
{
    /**
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     *
     * @return JsonResponse
     */

    private const REQUIRED_HEADERS = ['name', 'company', 'greeting_message', 'image'];
    public function __invoke(array $data): JsonResponse
    {
        return \DB::transaction(function () use ($data) {
            $file = $data['file'];
            $content = $data['content'];

            try {
                $filename = 'temp/import_' . Str::random(40) . '.csv';
                $path = $file->storeAs('', $filename);

                $csv = Reader::createFromPath(storage_path('app/' . $path), 'r');
                $csv->setHeaderOffset(0);

                $headers = $csv->getHeader();
                $missingHeaders = array_diff((array)self::REQUIRED_HEADERS, (array)$headers);

                if (! empty($missingHeaders)) {
                    @unlink(storage_path('app/' . $path));
                    return $this->httpBadRequest('必要なヘッダーが見つかりません: ' . implode(', ', $missingHeaders));
                }

                $records = iterator_to_array($csv->getRecords());
                if (empty($records)) {
                    @unlink(storage_path('app/' . $path));
                    return $this->httpBadRequest('登録または更新または削除できるデータがありません。');
                }
                ImportCardJob::dispatch($path, $content, auth()->user()->id);
                return $this->httpNoContent();
            } catch (\Exception $e) {
                if (isset($path)) {
                    @unlink(storage_path('app/' . $path));
                }

                return $this->httpBadRequest('CSVファイルの処理中にエラーが発生しました: ' . $e->getMessage());
            }
        });
    }
}
