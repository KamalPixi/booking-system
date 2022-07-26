<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BangladeshDistrict;
use App\Models\BangladeshDivision;
use App\Models\AdminFee;
use App\Models\Airline;
use App\Enums\AdminFeeEnum;
use App\Enums\TransactionEnum;

class ProjectInit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        # insert divisions
        $divisions = json_decode(file_get_contents(storage_path() . "/app/project/divisions.json"), true);
        foreach ($divisions['divisions'] as $d) {
            BangladeshDivision::firstOrCreate([
                'name' => $d['name']
            ]);
        }

        # insert district
        $districts = json_decode(file_get_contents(storage_path() . "/app/project/districts.json"), true);
        foreach ($districts['districts'] as $d) {
            BangladeshDistrict::firstOrCreate([
                'name' => $d['name']
            ]);
        }

        # insert airlines
        $airlines = json_decode(file_get_contents(storage_path() . "/app/project/airlines.json"), true);
        foreach ($airlines['AirlineInfo'] as $a) {
            Airline::firstOrCreate([
                'code' => $a['AirlineCode'],
                'name' => $a['AlternativeBusinessName']
            ]);
        }

        # insert admin fees
        $fees = [
            [
                'name' => 'A.I.T Tax',
                'fee_key' => AdminFeeEnum::KEY[0],
                'fee' => 0.30,
                'type' => TransactionEnum::METHOD_FEE_TYPE['PERCENTAGE'],
            ],
            [
                'name' => 'Flight Commission',
                'fee_key' => AdminFeeEnum::KEY[1],
                'fee' => 7,
                'type' => TransactionEnum::METHOD_FEE_TYPE['PERCENTAGE'],
            ],
        ];
        foreach ($fees as $fee) {
            AdminFee::firstOrCreate([
                'name' => $fee['name'],
                'fee_key' => $fee['fee_key'],
                'fee' => $fee['fee'],
                'type' => $fee['type'],
            ]);
        }


        echo 'Project init completed';
        return 0;
    }
}
