<?php


namespace App\Services;

use Telegram;
use App\Update;
use App\Facades\TelegramUpdatesHandlerService;

class TelegramUpdatesCronService
{
    const PERIOD_IN_SEC = 3;
    const CRON_FILE_PATH = "storage/TelegramUpdatesCronFile.txt";

    public function start()
    {
        $this->createCronFile();
        while ($this->cronFileExists()) {
            $this->make();
            sleep(self::PERIOD_IN_SEC);
        }
    }

    public function stop()
    {
        $this->deleteCronFile();
    }

    private function make()
    {
        foreach($this->getUpdates() as $telegramUpdate)
        {
            $update = Update::firstOrCreate(["update_id" => $telegramUpdate["update_id"]]);
            if (! $update->handled) {
                TelegramUpdatesHandlerService::handle($telegramUpdate);
                $update->setHandled();
            }
        }
    }

    private function getUpdates()
    {
        return Telegram::getUpdates();
    }

    private function createCronFile()
    {
        file_put_contents(self::CRON_FILE_PATH, "1");
    }

    private function deleteCronFile()
    {
        unlink(self::CRON_FILE_PATH);
    }

    public function cronFileExists()
    {
        return file_exists(self::CRON_FILE_PATH);
    }
}