<?php

namespace Tjgazel\LaraFpdf;


use Tjgazel\LaraFpdf\Fpdf\FPDF;

class LaraFpdf extends FPDF
{

    public $totalLargura;
    public $margemEsquerda;
    public $margemDireita;
    public $totalAltura;
//    Demais atributos

    protected $situacaoAprovado;
    protected $cargaHoraria;
    protected $diasLetivos;

    public function getTotalLargura()
    {
        return $this->totalLargura;
    }

    public function getMargemEsquerda()
    {
        return $this->margemEsquerda;
    }

    public function getMargemDireita()
    {
        return $this->margemDireita;
    }

    public function getTotalAltura()
    {
        return $this->totalAltura;
    }

    public function setTotalLargura($totalLargura)
    {
        $this->totalLargura = $totalLargura;
    }

    public function setMargemEsquerda($margemEsquerda)
    {
        $this->margemEsquerda = $margemEsquerda;
    }

    public function setMargemDireita($margemDireita)
    {
        $this->margemDireita = $margemDireita;
    }

    public function setTotalAltura($totalAltura)
    {
        $this->totalAltura = $totalAltura;
    }

    public function setOficio($larg = 216, $altu = 330)
    {
        $this->setTotalAltura($larg);
        $this->setTotalLargura($altu);
    }

    public function setA4($larg = 210, $altu = 297)
    {
        $this->setTotalAltura($larg);
        $this->setTotalLargura($altu);
    }

    public function getDocumentRoot()
    {
        return $_SERVER['DOCUMENT_ROOT'];
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

    var $angle = 0;

    function Rotate($angle, $x = -1, $y = -1)
    {
        if ($x == -1)
            $x = $this->x;
        if ($y == -1)
            $y = $this->y;
        if ($this->angle != 0)
            $this->_out('Q');
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

    function Rotate2($angle, $X = 0, $L = 0)
    {
        $x = $this->x + $X; // - $Y;
        $y = $this->y + $X;
        if ($this->angle != 0)
            $this->_out('Q');
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

    function CellRota($X, $Y, $text, $bordas, $alinha, $angle, $baixe = 0, $ln = 0)
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
        $this->Cell($Y, $X, $text, $bordas, 0, $alinha);
        $this->_out('Q');
        $x = $nx;
        $this->SetXY($x + $X, $y);
        if ($ln) {
            $this->Cell(1, $Y, '', '', 1, '');
        }
    }

    function MultiRota($X, $Y, $text, $bordas, $alinha, $angle, $baixe = 0, $ln = 0, $subLines = 3)
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
        $this->MultiCell($Y, $X / $subLines, $text, $bordas, $alinha);
        $this->_out('Q');
        $x = $nx;
        $this->SetXY($x + $X, $y);
        if ($ln) {
            $this->Cell(1, $Y, '', '', 1, '');
        }
    }

    function _endpage()
    {
        if ($this->angle != 0) {
            $this->angle = 0;
            $this->_out('Q');
        }
        parent::_endpage();
    }

    public function celX($larg)
    {
        return ($larg * ($this->w - $this->lMargin - $this->rMargin)) / 100;
    }

    public function posX($larg)
    {
        $this->SetX($this->celX($larg) + $this->lMargin);
    }


############################### Funçoes do Diário ######################################

    public function getBrasao($img = 'logo-instituicao.jpg')
    {
        return $this->getDocumentRoot() . '/img/' . $img;
    }

}

//gg
