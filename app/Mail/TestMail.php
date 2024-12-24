<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TestMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public array $data)
    {
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->data['subject'],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $processedContent = $this->data['content'];

        $processedContent = str_replace(
            ['__Email__', '__氏名__'],
            [$this->data['email'], ''],
            $processedContent
        );

        $processedContent = $this->prepareHtmlContent($processedContent);

        return new Content(
            view: 'emails.send-test',
            with: [
                'subject' => $this->data['subject'],
                'content' => $processedContent,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }

    private function prepareHtmlContent($content) {
        // Parse HTML content
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $xpath = new \DOMXPath($dom);
        
        $allStyles = '';
        $styleNodes = $xpath->query('//style');
        foreach ($styleNodes as $style) {
            $allStyles .= $style->nodeValue . "\n";
            $style->parentNode->removeChild($style);
        }

        $containers = $xpath->query("//*[contains(@class, 'image-text-container')]");
        foreach ($containers as $container) {
            $img = $xpath->query(".//img", $container)->item(0);
            $text = $xpath->query(".//div[contains(@class, 'ms5')]", $container)->item(0);
            
            if ($img && $text) {
                $mainTable = $dom->createElement('table');
                $mainTable->setAttribute('width', '600');
                $mainTable->setAttribute('border', '0');
                $mainTable->setAttribute('cellpadding', '0');
                $mainTable->setAttribute('cellspacing', '0');
                $mainTable->setAttribute('align', 'center');

                $imgTr = $dom->createElement('tr');
                $imgTd = $dom->createElement('td');
                $imgTd->setAttribute('align', 'center');
                
                $newImg = $img->cloneNode(true);
                $newImg->setAttribute('width', '480');
                $newImg->setAttribute('style', 'display: block; margin: 0 auto;');
                
                $imgTd->appendChild($newImg);
                $imgTr->appendChild($imgTd);
                $mainTable->appendChild($imgTr);

                $textTr = $dom->createElement('tr');
                $textTd = $dom->createElement('td');
                
                $innerTable = $dom->createElement('table');
                $innerTable->setAttribute('width', '100%');
                $innerTable->setAttribute('border', '0');
                $innerTable->setAttribute('cellpadding', '0');
                $innerTable->setAttribute('cellspacing', '0');
                
                $innerTr = $dom->createElement('tr');
                
                $spacerTd = $dom->createElement('td');
                $spacerTd->setAttribute('width', '200');
                $innerTr->appendChild($spacerTd);
                
                $contentTd = $dom->createElement('td');
                $contentTd->setAttribute('align', 'left');
                
                $newText = $text->cloneNode(true);
                $newText->setAttribute('style', 'color: #FFFFFF; font-size: 80px; font-weight: bold;');
                
                $contentTd->appendChild($newText);
                $innerTr->appendChild($contentTd);
                
                $spacerTd2 = $dom->createElement('td');
                $spacerTd2->setAttribute('width', '200');
                $innerTr->appendChild($spacerTd2);
                
                $innerTable->appendChild($innerTr);
                $textTd->appendChild($innerTable);
                $textTr->appendChild($textTd);
                
                $textTr->setAttribute('style', 'margin-top: -200px; position: relative;');
                $mainTable->appendChild($textTr);

                $container->parentNode->replaceChild($mainTable, $container);
            }
        }

        $html = $dom->saveHTML();
        
        $styleTag = '<style type="text/css" data-inline="true">' . $allStyles . '</style>';
        $html = preg_replace('/<head>/i', '<head>' . $styleTag, $html);

        // Wrap body content
        $html = preg_replace(
            '/<body([^>]*)>(.*?)<\/body>/is',
            '<body$1>
                <table width="100%" border="0" cellpadding="0" cellspacing="0" style="min-width:100%;">
                    <tr>
                        <td align="center" valign="top" style="padding:0;">
                            <table width="600" border="0" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;">
                                <tr>
                                    <td align="left" valign="top" style="padding:0;position:relative;">
                                        $2
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </body>',
            $html
        );

        return $html;
    }
}
