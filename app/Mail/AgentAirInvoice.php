<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;
use \NumberFormatter;


class AgentAirInvoice extends Mailable implements ShouldQueue {
    
    use Queueable, SerializesModels;

    public $title = 'Air Booking Invoice';
    public $booking;
    public $flight;
    public $invoice_no;

    public function __construct($booking, $flight, $invoice_no) {
        $this->booking = $booking;
        $this->flight = $flight;
        $this->invoice_no = $invoice_no;
    }

    public function build() {
        $digit = new NumberFormatter("en", NumberFormatter::SPELLOUT);
        $this->flight['pricingInfo']['totalPriceInWord'] = $digit->format($this->flight['pricingInfo']['totalPrice']);
        
        # generate pdf
        $pdf = Pdf::loadView('agent.prints.flight-booking-invoice', [
            'booking' => $this->booking,
            'flight' => $this->flight,
            'invoice_no' => $this->invoice_no,
        ]);
        
        return $this->subject($this->title .' booking: '. $this->booking->reference)
        ->view('agent.prints.flight-booking-invoice')
        ->attachData($pdf->output(), $this->booking->reference.'.pdf', ['mime' => 'application/pdf']);
    }
}
