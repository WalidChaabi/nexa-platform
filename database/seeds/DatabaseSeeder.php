<?php

use App\Models\Activity;
use App\Models\Campaign;
use App\Models\Categorie;
use App\Models\Customer;
use App\Models\Objective;
use App\Models\Product;
use App\models\Store;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user = [
            [
                'lastname' => 'walid',
                'firstname' => 'admin',
                'phone' => '0601340846',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('123456'),
                'is_admin' => 1,
                'store_id' => null,
                'is_active' => 1
            ],
            [
                'lastname' => 'walid',
                'firstname' => 'owner',
                'phone' => '0601340846',
                'email' => 'owner@gmail.com',
                'password' => Hash::make('123456'),
                'is_admin' => 2,
                'store_id' => null,
                'is_active' => 1
            ],
            [
                'lastname' => 'walid',
                'firstname' => 'emp',
                'phone' => '0601340846',
                'email' => 'emp@gmail.com',
                'password' => Hash::make('123456'),
                'is_admin' => 3,
                'store_id' => 1,
                'is_active' => 1
            ],

        ];
        User::insert($user);
        $store = [
            [
                'user_id' => 2,
                'activity_id' => 1,
                'logo' => null,
                'name' => 'walidstore',
                'phone' => '0601340846',
                'contact' => 'concat',
                'address' => 'Marrakech',
                'base' => 100,
                'base_profit' => 5,
                'coeff' => 12,
                'tva' => 0,
                'status' => 1,
                'invoice_message' => 'Merci pour votre entreprise! 
                    Le paiement est prévu dans les 31 jours; veuillez 
                    traiter cette facture dans ce délai. Il y aura une 
                    charge de 5 intérêts par mois sur les factures en retard.',
                'allow_camp' => 1,
                'sender_id' => 'dev',
                'reste' => 1,
                'rendez_vous' => 1,
                'praticien' => 1
            ]
        ];
        Store::insert($store);
        $categorie = [
            [
                'name' => 'categorie',
                'description' => 'categorie',
            ],
        ];
        Categorie::insert($categorie);
        $product = [
            [
                'store_id' => 1,
                'categorie_id' => 1,
                'label' => 'produit1',
                'price' => 200,
                'promotion_price' => 150,
                'quantite' => 100,
                'image' => 100,
            ],
        ];
        Product::insert($product);

        $customer = [
            [
                'store_id' => 1,
                'user_id' => 0,
                'phone' => '+212601340846',
                'email' => 'client@test.com',
                'firstname' => 'client',
                'lastname' => 'test',
                'sexe' => 0,
                'birth' => '1998-03-06',
                'points' => 0
            ],
        ];
        Customer::insert($customer);
        $campaigns = [
            [
                'name' => 'birthday',
                'type' => 'birthday',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'rendez-vous',
                'type' => 'rendez-vous',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'cross-selling',
                'type' => 'cross-selling',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'credit-sup',
                'type' => 'credit-sup',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'unique',
                'type' => 'unique',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];
        Campaign::insert($campaigns);
        $activities = [
            [
                'name' => 'Restauration & snack',
                'description' => 'Restauration & snack',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ];
        Activity::insert($activities);
        $objectives = [
            [
                'user_id' => 3,
                'daily' => 200,
                'weekly' => 1000,
                'monthly' => 10000,
            ]
        ];
        Objective::insert($objectives);
    }
}
