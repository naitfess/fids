<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AirportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $airports = [
            ['code' => 'AMQ', 'name' => 'Pattimura', 'area_code' => '31.71.01.1006', 'latitude' => '-3.7048965', 'longitude' => '128.0862563'],
            ['code' => 'BPN', 'name' => 'SAMS Sepinggan', 'area_code' => '31.71.01.1006', 'latitude' => '-1.2677976', 'longitude' => '116.8916491'],
            ['code' => 'BTJ', 'name' => 'Sultan Iskandar Muda', 'area_code' => '31.71.01.1006', 'latitude' => '5.5233', 'longitude' => '95.4173'],
            ['code' => 'BDJ', 'name' => 'Syamsudin Noor', 'area_code' => '31.71.01.1006', 'latitude' => '-3.4423', 'longitude' => '114.7634'],
            ['code' => 'BWX', 'name' => 'Banyuwangi', 'area_code' => '31.71.01.1006', 'latitude' => '-8.3091', 'longitude' => '114.3741'],
            ['code' => 'BTH', 'name' => 'Hang Nadim', 'area_code' => '31.71.01.1006', 'latitude' => '1.1212', 'longitude' => '104.1189'],
            ['code' => 'BKS', 'name' => 'Fatmawati Soekarno', 'area_code' => '31.71.01.1006', 'latitude' => '-3.8631', 'longitude' => '102.3386'],
            ['code' => 'BEJ', 'name' => 'Kalimarau', 'area_code' => '31.71.01.1006', 'latitude' => '2.1537', 'longitude' => '117.4344'],
            ['code' => 'BIK', 'name' => 'Frans Kaisiepo', 'area_code' => '31.71.01.1006', 'latitude' => '-1.1897', 'longitude' => '136.1070'],
            ['code' => 'BMU', 'name' => 'Sultan Muhammad Salahuddin', 'area_code' => '31.71.01.1006', 'latitude' => '-8.5401', 'longitude' => '118.6826'],
            ['code' => 'DPS', 'name' => 'I Gusti Ngurah Rai', 'area_code' => '31.71.01.1006', 'latitude' => '-8.7482', 'longitude' => '115.1672'],
            ['code' => 'ENE', 'name' => 'Haji Hasan Aroeboesman', 'area_code' => '31.71.01.1006', 'latitude' => '-8.8488', 'longitude' => '121.6619'],
            ['code' => 'GTO', 'name' => 'Djalaluddin', 'area_code' => '31.71.01.1006', 'latitude' => '0.6372', 'longitude' => '122.8496'],
            ['code' => 'GNS', 'name' => 'Binaka', 'area_code' => '31.71.01.1006', 'latitude' => '1.1542', 'longitude' => '97.7047'],
            ['code' => 'CGK', 'name' => 'Soekarno - Hatta', 'area_code' => '31.71.01.1006', 'latitude' => '-6.1256', 'longitude' => '106.6560'],
            ['code' => 'HLP', 'name' => 'Halim Perdanakusuma', 'area_code' => '31.71.01.1006', 'latitude' => '-6.2694', 'longitude' => '106.8902'],
            ['code' => 'DJB', 'name' => 'Sultan Thaha Syaifuddin', 'area_code' => '31.71.01.1006', 'latitude' => '-1.6380', 'longitude' => '103.6448'],
            ['code' => 'DJJ', 'name' => 'Sentani', 'area_code' => '31.71.01.1006', 'latitude' => '-2.5769', 'longitude' => '140.5161'],
            ['code' => 'KDI', 'name' => 'Haluoleo', 'area_code' => '31.71.01.1006', 'latitude' => '-4.1166', 'longitude' => '122.4208'],
            ['code' => 'KTG', 'name' => 'Rahadi Oesman', 'area_code' => '31.71.01.1006', 'latitude' => '-0.8524', 'longitude' => '109.9576'],
            ['code' => 'YIA', 'name' => 'New Yogyakarta International Airport', 'area_code' => '31.71.01.1006', 'latitude' => '-7.8860', 'longitude' => '110.0535'],
            ['code' => 'KOE', 'name' => 'El Tari', 'area_code' => '31.71.01.1006', 'latitude' => '-10.1706', 'longitude' => '123.6713'],
            ['code' => 'LBJ', 'name' => 'Komodo', 'area_code' => '31.71.01.1006', 'latitude' => '-8.4839', 'longitude' => '119.8907'],
            ['code' => 'LOP', 'name' => 'Zainuddin Abdul Madjid Lombok', 'area_code' => '31.71.01.1006', 'latitude' => '-8.7570', 'longitude' => '116.2764'],
            ['code' => 'LLJ', 'name' => 'Silampari', 'area_code' => '31.71.01.1006', 'latitude' => '-3.2809', 'longitude' => '102.9150'],
            ['code' => 'LUW', 'name' => 'Syukuran Aminudin Amir', 'area_code' => '31.71.01.1006', 'latitude' => '-1.0119', 'longitude' => '122.7711'],
            ['code' => 'KJT', 'name' => 'Kertajati', 'area_code' => '31.71.01.1006', 'latitude' => '-6.6669', 'longitude' => '108.1630'],
            ['code' => 'UPG', 'name' => 'Sultan Hasanuddin', 'area_code' => '31.71.01.1006', 'latitude' => '-5.0617', 'longitude' => '119.5540'],
            ['code' => 'MLG', 'name' => 'Abdul Rachman Saleh', 'area_code' => '31.71.01.1006', 'latitude' => '-7.9264', 'longitude' => '112.7144'],
            ['code' => 'MDC', 'name' => 'Sam Ratulangi', 'area_code' => '31.71.01.1006', 'latitude' => '1.5492', 'longitude' => '124.9265'],
            ['code' => 'MKW', 'name' => 'Rendani', 'area_code' => '31.71.01.1006', 'latitude' => '-0.8911', 'longitude' => '134.0510'],
            ['code' => 'MOF', 'name' => 'Fransiskus Xaverius Seda', 'area_code' => '31.71.01.1006', 'latitude' => '-8.6369', 'longitude' => '122.9550'],
            ['code' => 'KNO', 'name' => 'Kualanamu Internasional', 'area_code' => '31.71.01.1006', 'latitude' => '3.6420', 'longitude' => '98.8804'],
            ['code' => 'MKQ', 'name' => 'Mopah', 'area_code' => '31.71.01.1006', 'latitude' => '-8.5199', 'longitude' => '140.4168'],
            ['code' => 'MOH', 'name' => 'Maleo', 'area_code' => '31.71.01.1006', 'latitude' => '-1.0167', 'longitude' => '121.6167'],
            ['code' => 'NBX', 'name' => 'Douw Aturure', 'area_code' => '31.71.01.1006', 'latitude' => '-3.3644', 'longitude' => '135.4950'],
            ['code' => 'PDG', 'name' => 'Internasional Minangkabau', 'area_code' => '31.71.01.1006', 'latitude' => '-0.8710', 'longitude' => '100.2806'],
            ['code' => 'PKY', 'name' => 'Tjilik Riwut', 'area_code' => '31.71.01.1006', 'latitude' => '-2.2274', 'longitude' => '113.9427'],
            ['code' => 'PLM', 'name' => 'Sultan Mahmud Badarudin II', 'area_code' => '31.71.01.1006', 'latitude' => '-2.8972', 'longitude' => '104.7009'],
            ['code' => 'PLW', 'name' => 'Mutiara Sis Al Jufri', 'area_code' => '31.71.01.1006', 'latitude' => '-0.9198', 'longitude' => '119.9142'],
            ['code' => 'PGK', 'name' => 'Depati Amir', 'area_code' => '31.71.01.1006', 'latitude' => '-2.1610', 'longitude' => '106.1388'],
            ['code' => 'PKN', 'name' => 'Iskandar', 'area_code' => '31.71.01.1006', 'latitude' => '-2.7448', 'longitude' => '111.6710'],
            ['code' => 'PKU', 'name' => 'Sultan Syarif Kasim II', 'area_code' => '31.71.01.1006', 'latitude' => '0.4608', 'longitude' => '101.4447'],
            ['code' => 'PNK', 'name' => 'Supadio', 'area_code' => '31.71.01.1006', 'latitude' => '-0.1508', 'longitude' => '109.4042'],
            ['code' => 'AAP', 'name' => 'APT Pranoto', 'area_code' => '31.71.01.1006', 'latitude' => '-0.3800', 'longitude' => '117.0544'],
            ['code' => 'SMQ', 'name' => 'H. Asan', 'area_code' => '31.71.01.1006', 'latitude' => '-2.9150', 'longitude' => '112.9231'],
            ['code' => 'SRG', 'name' => 'Ahmad Yani', 'area_code' => '31.71.01.1006', 'latitude' => '-6.9749', 'longitude' => '110.3756'],
            ['code' => 'DTB', 'name' => 'Sisingamangaraja XII', 'area_code' => '31.71.01.1006', 'latitude' => '2.2599', 'longitude' => '98.8872'],
            ['code' => 'SOC', 'name' => 'Adi Soemarmo', 'area_code' => '31.71.01.1006', 'latitude' => '-7.5164', 'longitude' => '110.7569'],
            ['code' => 'SOQ', 'name' => 'Domine Edward Osok', 'area_code' => '31.71.01.1006', 'latitude' => '-0.8908276', 'longitude' => '131.2884765'],
            ['code' => 'SUB', 'name' => 'Juanda', 'area_code' => '31.71.01.1006', 'latitude' => '-7.3801', 'longitude' => '112.7888'],
            ['code' => 'TMC', 'name' => 'Lede Kalumbang', 'area_code' => '31.71.01.1006', 'latitude' => '-10.7183', 'longitude' => '120.3019'],
            ['code' => 'TKG', 'name' => 'Raden Inten II', 'area_code' => '31.71.01.1006', 'latitude' => '-5.2423', 'longitude' => '105.1783'],
            ['code' => 'TJQ', 'name' => 'H. A. S. Hanandjoeddin', 'area_code' => '31.71.01.1006', 'latitude' => '-2.7461', 'longitude' => '107.7547'],
            ['code' => 'TNJ', 'name' => 'Raja Haji Fisabilillah', 'area_code' => '31.71.01.1006', 'latitude' => '0.9237', 'longitude' => '104.5313'],
            ['code' => 'TRK', 'name' => 'Juwata', 'area_code' => '31.71.01.1006', 'latitude' => '3.3271', 'longitude' => '117.5658'],
            ['code' => 'TTE', 'name' => 'Sultan Babullah', 'area_code' => '31.71.01.1006', 'latitude' => '0.8314', 'longitude' => '127.3806'],
            ['code' => 'TIM', 'name' => 'Mozes Kilangin', 'area_code' => '31.71.01.1006', 'latitude' => '-4.5293', 'longitude' => '136.8884'],
            ['code' => 'WMX', 'name' => 'Wamena', 'area_code' => '31.71.01.1006', 'latitude' => '-4.1018', 'longitude' => '138.9500'],
            ['code' => 'JOG', 'name' => 'Adisutjipto', 'area_code' => '31.71.01.1006', 'latitude' => '-7.7882', 'longitude' => '110.4313'],
        ];


        foreach ($airports as $airport) {
            \App\Models\Airport::create($airport);
        }
    }
}
