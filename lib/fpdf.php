<?php
/*
 * Minimal FPDF class (version 1.82 compatible subset).
 * This is a compact copy to allow generating simple PDFs.
 * Source: adapted for embedding in project.
 */
class FPDF
{
    protected $page;
    protected $n;
    protected $offsets;
    protected $buffer;
    protected $pages;
    protected $state;
    protected $k = 1;
    protected $DefOrientation = 'P';
    protected $CurOrientation = 'P';
    protected $PageFormats = array();
    protected $FontFiles = array();
    protected $fonts = array();
    protected $FontFamily;
    protected $FontStyle;
    protected $FontSizePt;
    protected $FontSize;
    protected $LineWidth = 0.2;
    protected $ws = 0;
    public function __construct($orientation = 'P', $unit = 'mm', $size = 'A4')
    {
        $this->page = 0;
        $this->n = 2;
        $this->buffer = '';
        $this->pages = array();
        $this->PageFormats['A4'] = array(210, 297);
        $this->k = ($unit == 'pt') ? 1 : 72 / 25.4;
        $this->CurOrientation = strtoupper($orientation);
        $this->FontSizePt = 12;
        $this->FontSize = 12 / $this->k;
        $this->AddFont('Arial', '', '');
    }
    // minimal AddFont stub to avoid fatal errors in this compact implementation
    public function AddFont($family, $style = '', $file = '')
    {
        // no-op for this minimal PDF generator; real FPDF loads font metrics
        $this->fonts[$family] = true;
    }
    public function AddPage()
    {
        $this->page++;
        $this->pages[$this->page] = '';
        $this->state = 2;
    }
    public function SetFont($family, $style = '', $size = 0)
    {
        $this->FontFamily = $family;
        $this->FontStyle = $style;
        if ($size > 0) {
            $this->FontSizePt = $size;
            $this->FontSize = $size / $this->k;
        }
    }
    public function Cell($w, $h = 0, $txt = '', $border = 0, $ln = 0, $align = '', $fill = false, $link = '')
    {
        $txt = $this->_escape($txt);
        $this->pages[$this->page] .= sprintf('BT /F1 %.2f Tf 50 750 Td (%s) Tj ET' . "\n", $this->FontSizePt, $txt);
    }

    // write text at absolute PDF coordinates (points)
    public function Text($x, $y, $txt)
    {
        $txt = $this->_escape($txt);
        // ensure page exists
        if (!$this->page)
            $this->AddPage();
        $this->pages[$this->page] .= sprintf('BT /F1 %.2f Tf %.2f %.2f Td (%s) Tj ET' . "\n", $this->FontSizePt, $x, $y, $txt);
    }
    public function Ln($h = null)
    {
    }
    protected function _escape($s)
    {
        return str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $s);
    }
    public function Output($name = 'doc.pdf', $dest = 'I')
    {
        $this->_putpages();
        $pdf = $this->buffer;
        if ($dest == 'I') {
            header('Content-Type: application/pdf');
            echo $pdf;
            return;
        }
        file_put_contents($name, $pdf);
    }
    protected function _putpages()
    {
        // Very small PDF generation: create single page with content from $this->pages[1]
        $content = isset($this->pages[1]) ? $this->pages[1] : '';
        // wrap content in a proper PDF content stream
        $stream = $content;

        $objs = [];
        // objects: 1=content,2=catalog,3=pages,4=page,5=font
        $objs[1] = "1 0 obj\n<< /Length " . strlen($stream) . " >>\nstream\n" . $stream . "\nendstream\nendobj\n";
        $objs[2] = "2 0 obj\n<< /Type /Catalog /Pages 3 0 R >>\nendobj\n";
        $objs[3] = "3 0 obj\n<< /Type /Pages /Kids [4 0 R] /Count 1 >>\nendobj\n";
        $objs[4] = "4 0 obj\n<< /Type /Page /Parent 3 0 R /MediaBox [0 0 595 842] /Resources << /Font << /F1 5 0 R >> >> /Contents 1 0 R >>\nendobj\n";
        $objs[5] = "5 0 obj\n<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>\nendobj\n";

        $buffer = "%PDF-1.3\n%\xE2\xE3\xCF\xD3\n";
        $offsets = [];
        foreach ($objs as $i => $o) {
            $offsets[$i] = strlen($buffer);
            $buffer .= $o;
        }

        $xrefPos = strlen($buffer);
        $buffer .= "xref\n0 " . (count($objs) + 1) . "\n0000000000 65535 f \n";
        for ($i = 1; $i <= count($objs); $i++) {
            $buffer .= sprintf("%010d 00000 n \n", $offsets[$i]);
        }

        $buffer .= "trailer\n<< /Size " . (count($objs) + 1) . " /Root 2 0 R >>\nstartxref\n" . $xrefPos . "\n%%EOF\n";

        $this->buffer = $buffer;
    }
}

?>