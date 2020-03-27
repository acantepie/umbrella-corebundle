[1mdiff --git a/Component/DataTable/Model/DataTable.php b/Component/DataTable/Model/DataTable.php[m
[1mindex 9fd1ddf..3d333ae 100644[m
[1m--- a/Component/DataTable/Model/DataTable.php[m
[1m+++ b/Component/DataTable/Model/DataTable.php[m
[36m@@ -62,19 +62,37 @@[m [mclass DataTable implements OptionsAwareInterface[m
      */[m
     private $query;[m
 [m
[32m+[m[32m    /**[m
[32m+[m[32m     * @var boolean[m
[32m+[m[32m     */[m
[32m+[m[32m    private $isCallback = false;[m
[32m+[m
     /**[m
      * @param Request $request[m
      */[m
     public function handleRequest(Request $request)[m
     {[m
[31m-        if ($this->toolbar) {[m
[31m-            $this->toolbar->handleRequest($request);[m
[31m-            $this->query = array_merge($request->query->all(), $this->toolbar->getData());[m
[31m-        } else {[m
[31m-            $this->query = $request->query->all();[m
[32m+[m[32m        $queryData = $request->query->all();[m
[32m+[m
[32m+[m[32m        if ($request->isXmlHttpRequest() && $request->isMethod('GET') && isset($queryData['draw'])) {[m
[32m+[m[32m            $this->isCallback = true;[m
[32m+[m[32m            if ($this->toolbar) {[m
[32m+[m[32m                $this->toolbar->handleRequest($request);[m
[32m+[m[32m                $this->query = array_merge($queryData, $this->toolbar->getData());[m
[32m+[m[32m            } else {[m
[32m+[m[32m                $this->query = $queryData;[m
[32m+[m[32m            }[m
         }[m
     }[m
 [m
[32m+[m[32m    /**[m
[32m+[m[32m     * @return bool[m
[32m+[m[32m     */[m
[32m+[m[32m    public function isCallback()[m
[32m+[m[32m    {[m
[32m+[m[32m        return $this->isCallback;[m
[32m+[m[32m    }[m
[32m+[m
     /**[m
      * @return DataTableResult[m
      */[m
[1mdiff --git a/Component/Toolbar/Type/AddSearchToolbarType.php b/Component/Toolbar/Type/AddSearchToolbarType.php[m
[1mindex 762f4d7..755e2a9 100644[m
[1m--- a/Component/Toolbar/Type/AddSearchToolbarType.php[m
[1m+++ b/Component/Toolbar/Type/AddSearchToolbarType.php[m
[36m@@ -51,7 +51,7 @@[m [mclass AddSearchToolbarType extends ToolbarType[m
             ->setDefault('add_xhr', true)[m
             ->setAllowedTypes('add_xhr', 'bool')[m
 [m
[31m-            ->setDefault('add_label', 'add_action')[m
[32m+[m[32m            ->setDefault('add_label', 'add')[m
             ->setAllowedTypes('add_label','string')[m
 [m
             ->setRequired('add_route')[m
