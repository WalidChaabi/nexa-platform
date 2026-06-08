    <?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductQuantitesTable extends Migration
{

    public function up()
    {
        Schema::create('product_quantites', function (Blueprint $table) {

            $table->increments('id');
            $table->string('quantite');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_quantites');
    }
}
