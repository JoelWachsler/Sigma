<?php
/**
 * Gives formulas
 *
 * @author Joel Wachsler
 * @date 2014-12-26
 * @version 1.0
 **/
class FormulasController extends Controller {

    public static function home($id = null)
    {
        return Formula::get_formula($id);
    }

    public function search()
    {
        // Search for formula in the database
        $result = Formula::search(preg_replace('/_/', ' ', Input::get('q')));
        $data = [];
        foreach($result as $item)
            array_push($data, array('value' => $item->name, 'data' => $item->id));
        return Response::json($data);
    }

    public function get_formulas()
    {
        return Response::json(Formula::all(array('id', 'name')));
    }

    public function get_formula_by_id()
    {
        return Response::json(Formula::find(Input::get('q')));
    }

    public function insert()
    {
        // Insert if not exist
        try
        {
            if (Input::get('id') != 'new')
            {
                $instance = Formula::firstOrNew(array('id' => Input::get('id')));
            }
            else
            {
                $instance = new Formula;
            }

            $instance->fill(Input::all());
            $instance->save();
            return Response::json($instance->id);
        }
        catch(Exception $e)
        {
            return Response::json($e->getMessage());
        }
    }
}
