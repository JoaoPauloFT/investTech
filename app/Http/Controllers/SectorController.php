<?php

namespace App\Http\Controllers;

use App\Models\Sector;
use Illuminate\Http\Request;

class SectorController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Sector $sector) {

        $sectors = $sector->all();

        return view('sector.index', compact('sectors'));
    }

    public function getByName(string $name)
    {
        return Sector::where('name', $name)->first();
    }

    public function sync(Sector $sector) {
        ini_set('user_agent','Mozilla/4.0 (compatible; MSIE 6.0)');
        $url = "https://www.fundamentus.com.br/buscaavancada.php";

        $doc = new \DOMDocument();
        $internalErrors = libxml_use_internal_errors(true);
        $success = $doc->loadHTMLFile($url);
        libxml_use_internal_errors($internalErrors);

        if($success) {
            foreach ($doc->getElementsByTagName('select') as $select)
            {
                if ($select->getAttribute('name') == "setor")
                {
                    foreach ($select->childNodes as $option) {
                        if ($option->nodeName == 'option' && !empty($option->nodeValue)) {
                            $this->save($sector, $option->nodeValue);
                        }
                    }
                    break;
                }
            }
        }

        return redirect()->route('sector');
    }

    public function save(Sector $sector, string $name) {

        $s = $sector->where('name', $name)->first();

        if (!$s) {
            $data['name'] = $name;
            $s = $sector->create($data);
        }

        return $s;
    }
}
