<?php

namespace TJGazel\LaraFpdf;

use TJGazel\LaraFpdf\Fpdf\FPDF;

class LaraFpdf extends FPDF
{
    protected $maxWidth;
    protected $maxHeight;
    protected $marginLeft;
    protected $marginRight;
    protected $angle = 0;

    public function getMaxWidth()
    {
        return $this->maxWidth;
    }

    public function getMaxHeight()
    {
        return $this->maxHeight;
    }

    public function getMarginLeft()
    {
        return $this->marginLeft;
    }

    public function getMarginRight()
    {
        return $this->marginRight;
    }

    public function getAngle()
    {
        return $this->angle;
    }

    public function setMaxWidth($maxWidth)
    {
        $this->maxWidth = $maxWidth;
    }

    public function setMaxHeight($maxHeight)
    {
        $this->maxHeight = $maxHeight;
    }

    public function setMarginLeft($marginLeft)
    {
        $this->marginLeft = $marginLeft;
    }

    public function setMarginRight($marginRight)
    {
        $this->marginRight = $marginRight;
    }

    public function setAngle($angle)
    {
        $this->angle = $angle;
    }

    public function setOficio($width = 216, $height = 330)
    {
        $this->setMaxHeight($width);
        $this->setMaxWidth($height);
    }

    public function setA4($width = 210, $height = 297)
    {
        $this->setMaxHeight($width);
        $this->setMaxWidth($height);
    }

    public function Cell($w, $h = 0, $txt = '', $border = 0, $ln = 0, $align = '', $fill = false, $link = '')
    {
        parent::Cell($w, $h, utf8_decode($txt), $border, $ln, $align, $fill, $link);
    }

    public function MultiCel($w, $h, $txt, $border = 0, $align = 'J', $fill = false)
    {
        parent::MultiCell($this->celX($w), $h, $txt, $border, $align, $fill);
    }

    public function Cel($w, $h = 0, $txt = '', $border = 0, $ln = 0, $align = '', $fill = false, $link = '')
    {
        parent::Cell($this->celX($w), $h, utf8_decode($txt), $border, $ln, $align, $fill, $link);
    }

    public function Rotate($angle, $x = -1, $y = -1)
    {
        if ($x == -1) {
            $x = $this->x;
        }

        if ($y == -1) {
            $y = $this->y;
        }

        if ($this->angle != 0) {
            $this->_out('Q');
        }

        $this->angle = $angle;
        if ($angle != 0) {
            $angle *= M_PI / 180;
            $c = cos($angle);
            $s = sin($angle);
            $cx = $x * $this->k;
            $cy = ($this->h - $y) * $this->k;
            $this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm', $c, $s, -$s, $c, $cx, $cy, -$cx, -$cy));
        }
    }

    public function Rotate2($angle, $X = 0, $L = 0)
    {
        $x = $this->x + $X; // - $Y;
        $y = $this->y + $X;
        if ($this->angle != 0) {
            $this->_out('Q');
        }

        $this->angle = $angle;

        if ($angle != 0) {
            $angle *= M_PI / 180;
            $c = cos($angle);
            $s = sin($angle);
            $cx = $x * $this->k;
            $cy = ($this->h - $y) * $this->k;
            $this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm', $c, $s, -$s, $c, $cx, $cy, -$cx, -$cy));
        }
    }

    public function CellRota($X, $Y, $text, $border, $alinha, $angle, $baixe = 0, $ln = 0)
    {
        $this->angle = $angle;
        $nx = $x = $this->GetX();
        $y = $this->GetY();
        $recuo = $x - $baixe;
        $r = 0; #Regulagem para posição negativa
        if ($recuo < 0) {
            $this->SetX($x + $Y);
            $x = $this->GetX();
            $recuo = $x - $baixe;
            $r = $Y;
        }
        if ($angle != 0) {
            $angle *= M_PI / 180;
            $c = cos($angle);
            $s = sin($angle);
            $cx = $x * $this->k;
            $cy = ($this->h - $y) * $this->k;
            $this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm', $c, $s, -$s, $c, $cx, $cy, -$cx, -$cy));
        }
        $this->SetXY($recuo, $y - $r);
        $this->Cell($Y, $X, $text, $border, 0, $alinha);
        $this->_out('Q');
        $x = $nx;
        $this->SetXY($x + $X, $y);
        if ($ln) {
            $this->Cell(1, $Y, '', '', 1, '');
        }
    }

    public function MultiRota($X, $Y, $text, $border, $alinha, $angle, $baixe = 0, $ln = 0, $subLines = 3)
    {
        $this->angle = $angle;
        $nx = $x = $this->GetX();
        $y = $this->GetY();
        $recuo = $x - $baixe;
        $r = 0;
        if ($recuo < 0) {
            $this->SetX($x + $Y);
            $x = $this->GetX();
            $recuo = $x - $baixe;
            $r = $Y;
        }
        if ($angle != 0) {
            $angle *= M_PI / 180;
            $c = cos($angle);
            $s = sin($angle);
            $cx = $x * $this->k;
            $cy = ($this->h - $y) * $this->k;
            $this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm', $c, $s, -$s, $c, $cx, $cy, -$cx, -$cy));
        }
        $this->SetXY($recuo, $y - $r);
        $this->MultiCell($Y, $X / $subLines, $text, $border, $alinha);
        $this->_out('Q');
        $x = $nx;
        $this->SetXY($x + $X, $y);
        if ($ln) {
            $this->Cell(1, $Y, '', '', 1, '');
        }
    }

    public function _endpage()
    {
        if ($this->angle != 0) {
            $this->angle = 0;
            $this->_out('Q');
        }
        parent::_endpage();
    }

    public function celX($width)
    {
        return ($width * ($this->w - $this->lMargin - $this->rMargin)) / 100;
    }

    public function posX($width)
    {
        $this->SetX($this->celX($width) + $this->lMargin);
    }

}