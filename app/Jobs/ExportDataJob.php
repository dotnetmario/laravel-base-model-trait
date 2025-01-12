<?php

namespace App\Jobs;

use App\Traits\BaseModelTrait;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;


use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ExportDataJob implements ShouldQueue
{
    // use Queueable;
    // use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, BaseModelTrait;
    use BaseModelTrait;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    protected $columns;
    protected $exportType;

    /**
     * Create a new job instance.
     *
     * @param \Illuminate\Database\Eloquent\Collection $data
     * @param array $columns
     * @param string $exportType
     */
    public function __construct($data, array $columns, string $exportType = 'csv')
    {
        $this->data = $data;
        $this->columns = $columns;
        $this->exportType = $exportType;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        // Define the path where the file will be stored
        $filePath = 'export_' . now()->timestamp . '.' . $this->exportType;

        $preparedData = $this->prepareDataForExport($this->data, $this->columns);
        $labels = array_column($this->columns, 'label');

        // Append the chunk of data to the file
        Excel::store(new class ($preparedData, $labels) implements FromArray, WithHeadings {
            private $data;
            private $columns;

            public function __construct(array $data, array $columns)
            {
                $this->data = $data;
                $this->columns = $columns;
            }

            public function array(): array
            {
                return $this->data;
            }

            public function headings(): array
            {
                return array_values($this->columns);
            }
        }, $filePath);


        // Notify the user or send an email with a download link, etc.
    }
}
