<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CONNEXION • MOEZ RESTO</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;400;900&family=Playfair+Display:ital,wght@0,900;1,900&display=swap" rel="stylesheet">
    
    <style>
        :root { 
            --premium-blue: #007cf0;
            --deep-navy: #030712;
            --glass-blue: rgba(0, 124, 240, 0.05);
        }
        
        body { 
            background-color: var(--deep-navy); 
            color: #ffffff; 
            font-family: 'Montserrat', sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            /* Animation de fond radiale bleue */
            background-image: 
                radial-gradient(circle at 0% 0%, rgba(0, 124, 240, 0.15) 0%, transparent 35%),
                radial-gradient(circle at 100% 100%, rgba(0, 124, 240, 0.1) 0%, transparent 35%);
        }

        .font-luxury { font-family: 'Playfair Display', serif; font-weight: 900; }

        /* Carte Glassmorphism Bleu */
        .premium-card {
            background: rgba(255, 255, 255, 0.02);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(0, 124, 240, 0.2);
            padding: 4rem;
            width: 100%;
            max-width: 450px;
            border-radius: 24px;
            box-shadow: 0 40px 100px rgba(0, 0, 0, 0.8);
            position: relative;
        }

        /* Inputs Modernes Bleus */
        .input-group { position: relative; margin-bottom: 2.5rem; }
        
        .input-field {
            width: 100%;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 15px 20px;
            border-radius: 12px;
            color: white;
            font-size: 14px;
            outline: none;
            transition: 0.3s;
        }

        .input-field:focus {
            border-color: var(--premium-blue);
            background: rgba(0, 124, 240, 0.05);
            box-shadow: 0 0 20px rgba(0, 124, 240, 0.2);
        }

        .input-label {
            position: absolute;
            top: -22px; left: 5px;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--premium-blue);
            font-weight: 700;
        }

        /* Bouton Bleu Royal */
        .btn-premium {
            width: 100%;
            padding: 18px;
            background: linear-gradient(135deg, #007cf0 0%, #00dfd8 100%);
            border: none;
            border-radius: 12px;
            color: white;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 3px;
            font-size: 12px;
            cursor: pointer;
            transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .btn-premium:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 124, 240, 0.4);
            filter: brightness(1.1);
        }

        /* Retour Accueil */
        .back-link {
            position: absolute;
            top: -50px;
            left: 0;
            color: rgba(255, 255, 255, 0.4);
            text-decoration: none;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 3px;
            display: flex;
            align-items: center;
            transition: 0.3s;
        }
        .back-link:hover { color: var(--premium-blue); }

        .logo-glow {
            text-shadow: 0 0 30px rgba(0, 124, 240, 0.6);
        }
    </style>
</head>
<body>

    <div class="premium-card">
        <a href="{{ route('index') }}" class="back-link">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Accueil
        </a>

        <div class="text-center mb-12">
            <h1 class="text-5xl font-luxury italic mb-2 logo-glow">Moez<span class="text-[#007cf0] not-italic">.</span></h1>
            <p class="text-[9px] uppercase tracking-[5px] text-gray-500">Premium Dining Experience</p>
        </div>

        <form action="{{ route('login') }}" method="POST">
            @csrf
            
            <div class="input-group">
                <label class="input-label">Email Professionnel</label>
                <input type="email" name="email" required class="input-field" placeholder="nom@exemple.com">
            </div>

            <div class="input-group">
                <label class="input-label">Mot de passe</label>
                <input type="password" name="password" required class="input-field" placeholder="••••••••">
            </div>

            <div class="flex justify-between items-center mb-10 text-[10px] uppercase tracking-widest">
                <label class="flex items-center text-gray-500 cursor-pointer hover:text-white transition">
                    <input type="checkbox" name="remember" class="mr-2 accent-[#007cf0]">
                    Se souvenir
                </label>
                <a href="#" class="text-[#007cf0] font-bold">Oublié ?</a>
            </div>

            <button type="submit" class="btn-premium">
                Se Connecter
            </button>
        </form>

        <div class="mt-12 pt-8 border-t border-white/5 text-center">
            <p class="text-[9px] uppercase tracking-[3px] text-gray-500 mb-4">Nouveau ici ?</p>
            <a href="{{ route('register') }}" class="text-[#007cf0] font-black text-[11px] uppercase tracking-[4px] hover:underline">
                Créer un compte
            </a>
        </div>
    </div>

</body>
</html>