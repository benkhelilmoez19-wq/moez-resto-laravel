<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * Affiche le contenu du panier.
     */
    public function index()
    {
        $cartItems = session()->get('cart', []);
        return view('cart', compact('cartItems'));
    }

    /**
     * Ajoute un plat au panier via la session.
     */
    public function add($id)
    {
        $dish = Dish::findOrFail($id);
        $cart = session()->get('cart', []);

        if(isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "name" => $dish->name,
                "quantity" => 1,
                "price" => $dish->price,
                "image" => $dish->image
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Plat ajouté au panier !');
    }

    /**
     * Modifie la quantité d'un article dans le panier.
     */
    public function update(Request $request, $id)
    {
        $cart = session()->get('cart');

        if(isset($cart[$id]) && $request->quantity > 0) {
            $cart[$id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
            return redirect()->back()->with('success', 'Quantité mise à jour.');
        }

        return redirect()->back()->with('error', 'Erreur lors de la mise à jour.');
    }

    /**
     * Supprime un article du panier.
     */
    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if(isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Article retiré du panier.');
    }

    /**
     * Affiche la page de paiement/validation (GET /checkout).
     */
    public function showCheckout()
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('index')->with('error', 'Votre panier est vide.');
        }

        $total = 0;
        foreach($cart as $details) {
            $total += $details['price'] * $details['quantity'];
        }

        return view('checkout', compact('cart', 'total'));
    }

    /**
     * Finalise et confirme la commande (POST /commande/confirmer).
     */
    public function confirmOrder(Request $request)
    {
        // 1. Validation des champs du formulaire de livraison
        $request->validate([
            'customer_name'    => 'required|string|max:255',
            'phone'            => 'required|string|max:20',
            'address'          => 'required|string|max:500',
            'notes'            => 'nullable|string|max:255',
        ]);

        $cart = session()->get('cart');
        if (empty($cart)) {
            return redirect()->route('index')->with('error', 'Votre panier est vide.');
        }

        try {
            DB::beginTransaction();

            // 2. Calculer le montant total
            $totalPrice = 0;
            foreach ($cart as $item) {
                $totalPrice += $item['price'] * $item['quantity'];
            }

            // 3. Préparation de l'adresse complète (concaténation pour votre champ SQL delivery_address)
            $fullAddress = "Client: " . $request->customer_name . 
                           " | Tél: " . $request->phone . 
                           " | Adresse: " . $request->address . 
                           ($request->notes ? " | Note: " . $request->notes : "");

            // 4. Création de la commande principale (Table orders)
            $order = Order::create([
                'user_id'          => Auth::id(),
                'total_price'      => $totalPrice, 
                'delivery_address' => $fullAddress,
                'status'           => 'pending', 
                'created_at'       => now(),
            ]);

            // 5. Création des articles de la commande (Table order_items)
            foreach ($cart as $id => $details) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'dish_id'  => $id,
                    'quantity' => $details['quantity'],
                    'price'    => $details['price'],
                ]);
            }

            DB::commit();

            // 6. Vider le panier après succès
            session()->forget('cart');

            return redirect()->route('index')->with('success', '🚀 Commande reçue ! Nous vous appellerons au ' . $request->phone);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erreur lors de la confirmation : ' . $e->getMessage());
        }
    }
}