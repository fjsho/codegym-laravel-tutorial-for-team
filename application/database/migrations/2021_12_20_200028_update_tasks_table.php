<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            //task_prioriry_idの初期値は2=優先度中
            $table->foreignId('task_priority_id')->nullable()->constrained('task_priorities')->default('2');
            $table->decimal('actual_time', 5, 2)->nullable();
            $table->string('task_urgency')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            //外部キーエラーが出るため、task_prioritiesテーブルと紐づいてるtask_priority_idカラムの外部キーを削除
            $table->dropForeign('tasks_task_priority_id_foreign');
            $table->dropColumn('task_priority_id');
            $table->dropColumn('actual_time');
            $table->dropColumn('task_urgency');
        });
    }
}
