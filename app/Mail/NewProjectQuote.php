<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Mail\Attachable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;


class NewProjectQuote extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $project;
    public function __construct(array $project)
    {
        $this->project = $project;
        // dd($this->project);
    }
    

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Project Quote',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.new-project-quote',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments()
    {
        // project_requirement
        // dd($this->project['project_requirement']);
        foreach($this->project['project_requirement'] as $file) {
            // $file = $this->project['project_requirement'];
            $file_name = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension(); 
            // $mimeType = $file->mimeType(); 

            // dd($extension);
            return [
                Attachment::fromPath($file->getRealPath())
                    ->as(basename($file->getClientOriginalName())) // Use the original filename for each attachment
                    // ->withMime($mimeType) // Set the MIME type
                    // ->withExtension($extension)
                // ->withMime('application/pdf')
            ];
            
        }
        
    }
}
