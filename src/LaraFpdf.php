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

    public function GetMaxWidth()
    {
        return $this->maxWidth;
    }

    public function GetMaxHeight()
    {
        return $this->maxHeight;
    }

    public function GetMarginLeft()
    {
        return $this->marginLeft;
    }

    public function GetMarginRight()
    {
        return $this->marginRight;
    }

    public function GetAngle()
    {
        return $this->angle;
    }

    public function SetMaxWidth($maxWidth)
    {
        $this->maxWidth = $maxWidth;
    }

    public function SetMaxHeight($maxHeight)
    {
        $this->maxHeight = $maxHeight;
    }

    public function SetMarginLeft($marginLeft)
    {
        $this->marginLeft = $marginLeft;
    }

    public function SetMarginRight($marginRight)
    {
        $this->marginRight = $marginRight;
    }

    public function SetAngle($angle)
    {
        $this->angle = $angle;
    }

    public function SetOficio($width = 216, $height = 330)
    {
        $this->setMaxHeight($width);
        $this->setMaxWidth($height);
    }

    public function SetA4($width = 210, $height = 297)
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
        parent::MultiCell($this->CelX($w), $h, $txt, $border, $align, $fill);
    }

    public function Cel($w, $h = 0, $txt = '', $border = 0, $ln = 0, $align = '', $fill = false, $link = '')
    {
        parent::Cell($this->CelX($w), $h, utf8_decode($txt), $border, $ln, $align, $fill, $link);
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

    public function CellRota($X, $Y, $text, $border, $alinha, $angle, $baixe = 0, $ln = 0)
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

    public function CelX($width)
    {
        return ($width * ($this->w - $this->lMargin - $this->rMargin)) / 100;
    }

    public function PosX($width)
    {
        $this->SetX($this->CelX($width) + $this->lMargin);
    }

    public function SetDarkFill($inverse = false)
    {
        if ($inverse) {
            $this->SetFillColor(204, 204, 204);
            $this->SetTextColor(34, 34, 34);
        } else {
            $this->SetFillColor(102, 102, 102);
            $this->SetTextColor(255, 255, 255);
        }
    }

    public function SetDefaultFill()
    {
        $this->SetFillColor(255, 255, 255);
        $this->SetTextColor(34, 34, 34);
    }

}