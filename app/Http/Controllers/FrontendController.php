<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\Canchas;
use App\Models\Reservacion;
use App\Models\TipoReservacion;
use Illuminate\Http\Request;


class FrontendController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clubes = Club::all();
        return view('frontend.index', compact('clubes'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function clubDetalle($id)
    {
        $club = Club::with("canchas")->findOrFail($id);
        $canchas = $club->canchas;
        return view('frontend.club', compact('club', 'canchas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        }
        


    

    
    /**
     * Display the specified resource.
     */
    public function show(Club $club)
    {
        //
    }


    public function edit(Club $club)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
public function update(Request $request, Club $club)
    {

        }
        

        


    /**
     * Remove the specified resource from storage.
     */
public function destroy(Club $club)
    {
        
    }

}
