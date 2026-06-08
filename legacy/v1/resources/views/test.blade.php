<h1>Payment form CMI</h1>
    <form method="post" action="{{route('owner.checkout-process')}}">
        @csrf
        <label for="commande">Commande</label>
        <input type="text" name="cmd" value="{{uniqid()}}"> <br>
    <label for="amount">Amount</label>
        <input type="text" name="amount" class="input-control" placeholder="put amount here 10.65" value="10.60"> DHS<br/>
        <button type="submit">Buy</button>
    </form>