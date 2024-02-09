<?php

namespace App\Http\Controllers;

use App\Models\Action;
use App\Models\Subsector;
use Illuminate\Http\Request;

class ActionController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Action $action) {

        $actions = $action->all();

        return view('action.index', compact('actions'));
    }

    public function listSync()
    {
        ini_set('user_agent','Mozilla/4.0 (compatible; MSIE 6.0)');

        $action = array();

        $url = "http://www.fundamentus.com.br/resultado.php";

        $doc = new \DOMDocument();
        $internalErrors = libxml_use_internal_errors(true);
        $success = $doc->loadHTMLFile($url);
        libxml_use_internal_errors($internalErrors);

        if($success) {
            $table = $doc->getElementById('resultado');
            $tbody = $table->childNodes[3];
            foreach ($tbody->childNodes as $tr) {
                if ($tr->nodeName == 'tr') {
                    $action[] = $tr->childNodes[1]->nodeValue;
                }
            }
        }

        return response()->json([
            'data' => $action,
            'qtdActions' => count($action),
        ]);
    }

    public function getAction(Request $request)
    {
        ini_set('user_agent','Mozilla/4.0 (compatible; MSIE 6.0)');

        $url = "https://www.fundamentus.com.br/detalhes.php?papel=".$request->action;

        $doc = new \DOMDocument();
        $internalErrors = libxml_use_internal_errors(true);
        $success = $doc->loadHTMLFile($url);
        libxml_use_internal_errors($internalErrors);

        $act = array();
        if($success) {

            $table = $doc->getElementsByTagName('table')->item(0);

            if(strtotime('-2 months') < strtotime($table->childNodes[3]->childNodes[7]->nodeValue)) {
                $sector = $table->childNodes[7]->childNodes[3]->nodeValue;
                $name_subsector = $table->childNodes[9]->childNodes[3]->nodeValue;

                $subsector = new SubsectorController();

                $act = new Action();

                $act = $act->where('ticker', $table->childNodes[1]->childNodes[3]->nodeValue)->first();

                $act->subsector_id = $subsector->save($name_subsector, $sector)->id;

                $act->ticker = $table->childNodes[1]->childNodes[3]->nodeValue;
                $act->type = $table->childNodes[3]->childNodes[3]->nodeValue;
                $act->name = $table->childNodes[5]->childNodes[3]->nodeValue;
                $act->nomenclature = substr($act->ticker, 0, 4);

                $act->save();
            }
        }

        return response()->json([
            'data' => $act,
        ]);
    }
}
