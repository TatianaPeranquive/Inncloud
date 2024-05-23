<?php

namespace App\Http\Controllers;

use App\Models\doc_documento;
use App\Models\pro_proceso;
use App\Models\tip_tipo_doc;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;


class doc_documento_controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lista_documentos = doc_documento::latest()->get();
        return view('index',[
            'lista_documentos'=> $lista_documentos
            ]);
    }

    /**
     * Crear consecutivo único
    */
    function generarCodigoConsecutivo()
    {
        $ultimo_codigo = doc_documento::latest()->value('doc_codigo');

        if (isset($ultimo_codigo) && !empty($ultimo_codigo)){
            $nuevo_codigo = $ultimo_codigo + 1;
        }else {
            $nuevo_codigo = 0;
        }

        while (doc_documento::where('doc_codigo', $nuevo_codigo)->exists()) {
            $nuevo_codigo++;
        }
        return $nuevo_codigo;
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pro_procesos = pro_proceso::latest()->get();
        $tip_tipo_docs = tip_tipo_doc::latest()->get();
        return view('create',[
        'pro_procesos'=> $pro_procesos,
        'tip_tipo_docs'=> $tip_tipo_docs,
        'nuevo_codigo'=> $this->generarCodigoConsecutivo()
        ]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request):RedirectResponse
    {
        $request->validate(([
            'doc_nombre' => 'required',
            'doc_codigo' => 'required'
        ]));
       //dd($request->all());// Imprime los datos ingresados en el formulario de create
       doc_documento::create($request->all());// Inserta los datos del request en la bd
       return redirect()->route('CRUD_documentos.index')->with('success', 'Docuemnto creado con éxito. ')
        ;
    }

    /**
     * Display the specified resource.
     */
    public function show(doc_documento $doc_documento)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(doc_documento $doc_documento)
    {
       return view('edit', ['doc_documento' => $doc_documento]); //dd($doc_documento);// 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, doc_documento $doc_documento)
    {
        return view('edit', ['doc_documento' => $doc_documento]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(doc_documento $doc_documento)
    {
        dd($doc_documento);
    }
}
