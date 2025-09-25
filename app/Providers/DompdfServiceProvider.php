<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Dompdf\Dompdf;
use Dompdf\Options;

class DompdfServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('dompdf', function ($app) {
            return new class {
                public function loadView($view, $data = [])
                {
                    $options = new Options();
                    $options->set('defaultFont', 'DejaVu Sans');
                    $options->set('isRemoteEnabled', true);
                    $options->set('isHtml5ParserEnabled', true);

                    $dompdf = new Dompdf($options);
                    $html = view($view, $data)->render();
                    $dompdf->loadHtml($html);
                    $dompdf->setPaper('A4', 'portrait');
                    $dompdf->render();

                    return new class($dompdf) {
                        private $dompdf;

                        public function __construct($dompdf)
                        {
                            $this->dompdf = $dompdf;
                        }

                        public function download($filename)
                        {
                            return response($this->dompdf->output())
                                ->header('Content-Type', 'application/pdf')
                                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
                        }

                        public function stream($filename)
                        {
                            return response($this->dompdf->output())
                                ->header('Content-Type', 'application/pdf')
                                ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
                        }
                    };
                }
            };
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}