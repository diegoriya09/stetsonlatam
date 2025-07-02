<div id="checkout-modal" class="modalcheckout" style="display:none;">
    <div class="modal-content-checkout">
        <span class="close-modal" id="cerrar-checkout">&times;</span>
        <h2>Checkout</h2>
        <form id="checkout-form">
        <label>Full Name:<br>
            <input type="text" name="nombre" required>
        </label><br>
        <label>Email:<br>
            <input type="email" name="email" required>
        </label><br>
        <label>Address:<br>
            <input type="text" name="direccion" required>
        </label><br>
        <label>City:<br>
            <input type="text" name="ciudad" required>
        </label><br>
        <label>Payment method:<br>
            <select name="metodo" required>
            <option value="">Select</option>
            <option value="pse">PSE (Simulated)</option>
            <option value="tarjeta">Card (Simulated)</option>
            </select>
        </label><br>
        <button type="submit" class="pagar-btn">Pay</button>
        </form>
        <div id="checkout-confirm" style="display:none; margin-top:1rem;"></div>
    </div>
</div>