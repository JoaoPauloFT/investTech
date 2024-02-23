<?php

namespace App\Http\Controllers;

use App\Models\ActionHistory;

use Illuminate\Http\Request;

class ActionHistoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(ActionHistory $actionHistory) {

        $actionHistories = $actionHistory->orderBy('created_at', 'desc')->get()->unique('action_id');

        return view('indicator.index', compact('actionHistories'));
    }

    public function getAllHistoryByActionOrdened(int $action_id) {
        $actionHistory = new ActionHistory();

        return $actionHistory->where('action_id', $action_id)->orderBy("created_at", "desc")->get();
    }

    public function sync(ActionHistory $actionHistory) {
        ini_set('user_agent','Mozilla/4.0 (compatible; MSIE 6.0)');

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

                    $action = new ActionController();
                    $action = $action->getByTicker($tr->childNodes[1]->nodeValue);

                    if ($action != null) {
                        $actionHistory = new ActionHistory();

                        $actionHistory->cotation = $this->formatValue($tr->childNodes[3]->nodeValue);
                        $actionHistory->pl = $this->formatValue($tr->childNodes[5]->nodeValue);
                        $actionHistory->pvp = $this->formatValue($tr->childNodes[7]->nodeValue);
                        $actionHistory->psr = $this->formatValue($tr->childNodes[9]->nodeValue);
                        $actionHistory->dividend_yield = $this->formatValue($tr->childNodes[11]->nodeValue);
                        $actionHistory->price_active = $this->formatValue($tr->childNodes[13]->nodeValue);
                        $actionHistory->price_working_capital = $this->formatValue($tr->childNodes[15]->nodeValue);
                        $actionHistory->price_ebit = $this->formatValue($tr->childNodes[17]->nodeValue);
                        $actionHistory->price_active_circ = $this->formatValue($tr->childNodes[19]->nodeValue);
                        $actionHistory->ev_ebit = $this->formatValue($tr->childNodes[21]->nodeValue);
                        $actionHistory->ev_ebitda = $this->formatValue($tr->childNodes[23]->nodeValue);
                        $actionHistory->margin_ebit = $this->formatValue($tr->childNodes[25]->nodeValue);
                        $actionHistory->liquid_margin = $this->formatValue($tr->childNodes[27]->nodeValue);
                        $actionHistory->current_liquidation = $this->formatValue($tr->childNodes[29]->nodeValue);
                        $actionHistory->roic = $this->formatValue($tr->childNodes[31]->nodeValue);
                        $actionHistory->roe = $this->formatValue($tr->childNodes[33]->nodeValue);
                        $actionHistory->liquidation_old_months = $this->formatValue($tr->childNodes[35]->nodeValue);
                        $actionHistory->net_worth = $this->formatValue($tr->childNodes[37]->nodeValue);
                        $actionHistory->gross_debt_patrimony = $this->formatValue($tr->childNodes[39]->nodeValue);
                        $actionHistory->recurring_growth = $this->formatValue($tr->childNodes[41]->nodeValue);
                        $actionHistory->action_id = $action->id;
                        $actionHistory->save();
                    }
                }
            }
        }

        return redirect()->route('indicator');
    }

    public function formatValue(string $value) {
        $new_value = str_replace(".", "", $value);
        $new_value = floatval(str_replace(",", ".", $new_value));
        if(str_contains($value, '%')) {
            $new_value *= 100;
        }
        return $new_value;
    }

    public function save(ActionHistory $actionHistory, string $name) {

        $s = $sector->where('name', $name)->first();

        if (!$s) {
            $data['name'] = $name;
            $s = $sector->create($data);
        }

        return $s;
    }
}
