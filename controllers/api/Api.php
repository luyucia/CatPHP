<?php
/**
 * @name ApiController
 * @author Luyu
 */
class ApiController extends Controller {


    public function Get() {
        // echo "<a href='Api/Api'>click</a>";
        $id = $this->getRequest(0,'monren');
        echo $id;
    }

    public function Post() {
        echo 'Post cat';
    }

    public function Put() {
        echo 'Put cat';
    }

    public function Delete() {
        echo 'Delete cat';
    }

    


}
