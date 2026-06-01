<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE orders DROP CONSTRAINT IF EXISTS orders_type_check");
        DB::statement("ALTER TABLE orders ADD CONSTRAINT orders_type_check CHECK (type::text = ANY (ARRAY['dine_in'::character varying, 'delivery'::character varying, 'pos'::character varying, 'takeaway'::character varying]::text[]))");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE orders DROP CONSTRAINT IF EXISTS orders_type_check");
        DB::statement("ALTER TABLE orders ADD CONSTRAINT orders_type_check CHECK (type::text = ANY (ARRAY['dine_in'::character varying, 'delivery'::character varying, 'pos'::character varying]::text[]))");
    }
};
