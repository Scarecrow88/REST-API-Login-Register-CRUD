<?php
    require_once __DIR__ . '/../models/ItemModel.php';
    class TokenController {
        private $model;
        public function __construct () {
            $this->model = new ItemsModel ();
        }
        public function updateToken ($dat) {
            $this->writeInput ($this->model->updateToken ($dat));
        }
        private function createTxt ($dir) {
           $file = fopen ($dir, 'w') or die ("Error al crear el archivo de registro");
           $text = "------------------------------------ Rec del CRON JOB ------------------------------------ \n";
           fwrite ($file, $text) or die ("No pudimos escribir el registro");
           fclose ($file);
        }
        private function writeInput ($rec) {
            $dir = "../record/recordToken.txt";
            if (!file_exists ($dir)) {
                $this->createTxt ($dir);
            }
            /* crear una entrada nueva */
            $this->writeTxt ($dir, $rec);
        }
        private function writeTxt ($dir, $rec) {
            $date = date ("Y-m-d H:i");
            $file = fopen ($dir, 'a') or die ("Error al abrir el archico de registro");
            $text = "Se modificaron $rec registro(s) el dia [$date] \n";
            fwrite ($file, $text) or die ("No pudimos escribir el registro");
            fclose ($file);
        }
    }
?>