<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HÉRITAGE • MOEZ RESTO</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;400;800&family=Playfair+Display:ital,wght@0,900;1,900&display=swap" rel="stylesheet">
    
    <style>
        :root { 
            --premium-blue: #007cf0;
            --deep-navy: #020617;
            --cyan: #00dfd8;
        }
        
        body { 
            background-color: var(--deep-navy); 
            color: #ffffff; 
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            margin: 0;
            background-image: 
                radial-gradient(circle at 10% 10%, rgba(0, 124, 240, 0.1) 0%, transparent 40%),
                radial-gradient(circle at 90% 90%, rgba(0, 223, 216, 0.05) 0%, transparent 40%);
        }

        .font-luxury { font-family: 'Playfair Display', serif; font-weight: 900; }

        /* Carte Glassmorphism */
        .register-card {
            background: rgba(255, 255, 255, 0.02);
            backdrop-filter: blur(25px);
            border: 1px solid rgba(0, 124, 240, 0.2);
            padding: 3.5rem;
            width: 100%;
            max-width: 850px;
            border-radius: 40px;
            box-shadow: 0 50px 100px rgba(0, 0, 0, 0.6);
            position: relative;
        }

        /* Groupes d'Inputs */
        .input-group { position: relative; margin-bottom: 2rem; }
        
        .input-field {
            width: 100%;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            padding: 16px 20px;
            border-radius: 16px;
            color: white;
            font-size: 14px;
            outline: none;
            transition: 0.4s;
        }

        .input-field:focus {
            border-color: var(--premium-blue);
            background: rgba(0, 124, 240, 0.05);
            box-shadow: 0 0 25px rgba(0, 124, 240, 0.15);
        }

        .input-label {
            display: block;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 3px;
            color: var(--premium-blue);
            font-weight: 800;
            margin-bottom: 10px;
            margin-left: 5px;
        }

        /* Bouton dégradé */
        .btn-register {
            width: 100%;
            padding: 20px;
            background: linear-gradient(135deg, #007cf0 0%, #00dfd8 100%);
            border: none;
            border-radius: 20px;
            color: white;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 4px;
            font-size: 12px;
            cursor: pointer;
            transition: 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            margin-top: 20px;
        }

        .btn-register:hover {
            transform: scale(1.02);
            box-shadow: 0 20px 40px rgba(0, 124, 240, 0.3);
            filter: brightness(1.1);
        }

        .text-blue-gradient {
            background: linear-gradient(to right, #007cf0, #00dfd8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Lien Retour */
        .back-link {
            position: absolute;
            top: 2rem;
            left: 2.5rem;
            color: rgba(255, 255, 255, 0.3);
            text-decoration: none;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 3px;
            display: flex;
            align-items: center;
            transition: 0.3s;
        }
        .back-link:hover { color: var(--premium-blue); }
    </style>
</head>
<body>

    <div class="register-card">
        <a href="{{ route('index') }}" class="back-link">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Quitter
        </a>

        <div class="text-center mb-14">
            <h1 class="text-5xl font-luxury italic mb-3">Moez<span class="text-blue-gradient not-italic">.</span></h1>
            <p class="text-[10px] uppercase tracking-[6px] text-gray-500">Créer votre héritage privilège</p>
            <div class="w-12 h-[2px] bg-gradient-to-r from-[#007cf0] to-[#00dfd8] mx-auto mt-6"></div>
        </div>

        <form action="{{ route('register') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-2">
                <div class="input-group">
                    <label class="input-label">Nom Complet</label>
                    <input type="text" name="name" required class="input-field" placeholder="ex: Moez Ben Khelil">
                </div>

                <div class="input-group">
                    <label class="input-label">Adresse Email</label>
                    <input type="email" name="email" required class="input-field" placeholder="nom@agence.com">
                </div>

                <div class="input-group">
                    <label class="input-label">Contact Téléphonique</label>
                    <input type="tel" name="phone" required class="input-field" placeholder="+216 ...">
                </div>

                <div class="input-group">
                    <label class="input-label">Ville de Résidence</label>
                    <input type="text" name="address" required class="input-field" placeholder="Tunis, FR, ...">
                </div>

                <div class="input-group">
                    <label class="input-label">Code d'accès</label>
                    <input type="password" name="password" required class="input-field" placeholder="••••••••">
                </div>

                <div class="input-group">
                    <label class="input-label">Vérification</label>
                    <input type="password" name="password_confirmation" required class="input-field" placeholder="••••••••">
                </div>
            </div>

            <div class="mt-6 mb-10 text-center">
                <p class="text-[9px] text-gray-600 uppercase tracking-widest leading-loose max-w-lg mx-auto">
                    En rejoignant l'antre de <span class="text-white">Moez Resto</span>, vous acceptez nos conditions d'excellence et de confidentialité.
                </p>
            </div>

            <button type="submit" class="btn-register">
                Confirmer l'adhésion
            </button>
        </form>

        <div class="mt-14 pt-8 border-t border-white/5 text-center">
            <a href="{{ route('login') }}" class="text-[10px] uppercase tracking-[4px] text-gray-500 hover:text-[#007cf0] transition">
                Déjà membre de l'élite ? <span class="text-[#007cf0] font-black ml-2 border-b border-[#007cf0] pb-1">S'identifier</span>
            </a>
        </div>
    </div>

</body>
</html>