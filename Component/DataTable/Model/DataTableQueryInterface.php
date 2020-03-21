<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 18/05/17
 * Time: 19:03.
 */

namespace Umbrella\CoreBundle\Component\DataTable\Model;

use Symfony\Component\HttpFoundation\Request;

interface DataTableQueryInterface
{

    /**
     * Build query.
     *
     * @param DataTable $table
     */
    public function build(DataTable $table);

    /**
     * @param Request $request
     * @param DataTable $table
     */
    public function handleRequest(Request $request, DataTable $table);

    /**
     * @return array
     *  data => result[]
     *  recordsTotal => Total records, before filtering
     *  recordsFiltered => Total records, after filtering
     */
    public function getResults();


}
