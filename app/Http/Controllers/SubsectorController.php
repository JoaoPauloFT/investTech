<?php

namespace App\Http\Controllers;

use App\Models\Sector;
use App\Models\Subsector;
use Illuminate\Http\Request;

class SubsectorController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Subsector $subsector) {

        $subsectors = $subsector->all();

        return view('subsector.index', compact('subsectors'));
    }

    public function save(string $name, string $name_sector) {

        $s = Subsector::where('name', $name)->first();

        $sector = new SectorController();
        $sec = $sector->getByName($name_sector);

        if (!$sec) {
            $sec = $sector->save(new Sector, $name_sector);
        }

        if (!$s) {
            $data['name'] = $name;
            $data['sector_id'] = $sec->id;
            $s = Subsector::create($data);
        } else {
            $s->sector_id = $sec->id;
            $s->save();
        }

        return $s;
    }
}
