<!DOCTYPE html>  
<html lang="en">  
<head>  
    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <title>This Page Painzy</title>  
    <style>  
        @import url('https://fonts.googleapis.com/css2?family=Share+Tech+Mono&display=swap');  
          
        * {  
            margin: 0;  
            padding: 0;  
            box-sizing: border-box;  
        }  
          
        body {  
            background: #000;  
            font-family: 'Share Tech Mono', monospace;  
            display: flex;  
            align-items: center;  
            justify-content: center;  
            min-height: 100vh;  
            text-align: center;  
            padding: 20px;  
            cursor: pointer; /* kasih tanda bisa diklik */  
        }  
          
        .container {  
            max-width: 600px;  
            padding: 20px;  
        }  
          
        .avatar {  
            width: 260px;  
            height: 260px;  
            margin: 0 auto 22px;  
            display: block;  
            border-radius: 50%;  
            object-fit: cover;  
            border: 6px solid #A9A9A9;  
            box-shadow: 0 0 12px #E6E6E6, 0 0 28px rgba(166, 166, 166, 0.35);  
            animation: glow 3s ease-in-out infinite;  
        }  
  
        @keyframes glow {  
            0%, 100% {  
                box-shadow: 0 0 12px #E6E6E6, 0 0 30px rgba(169, 169, 169, 0.25);  
            }  
            50% {  
                box-shadow: 0 0 24px #FFFFFF, 0 0 60px rgba(255, 255, 255, 0.5);  
            }  
        }  
  
        h1 {  
            margin: 8px 0 10px;  
            font-size: clamp(22px, 5vw, 36px);  
            letter-spacing: 0.18em;  
            text-transform: uppercase;  
            background: linear-gradient(to bottom, #E6E6E6, #A9A9A9, #4D4D4D);  
            -webkit-background-clip: text;  
            -webkit-text-fill-color: transparent;  
            text-shadow: 0 0 14px rgba(255, 255, 255, 0.5), 0 0 40px rgba(200, 200, 200, 0.3);  
        }  

        .palestine {  
            background: linear-gradient(270deg, #D32F2F, #FFFFFF, #388E3C, #000000, #D32F2F);  
            background-size: 400% 400%;  
            -webkit-background-clip: text;  
            -webkit-text-fill-color: transparent;  
            font-weight: bold;  
            text-shadow: 0 0 14px rgba(211, 47, 47, 0.6),  
                         0 0 28px rgba(56, 142, 60, 0.5);  
            animation: palestineWave 6s ease-in-out infinite;  
        }  

        @keyframes palestineWave {  
            0% { background-position: 0% 50%; }  
            50% { background-position: 100% 50%; }  
            100% { background-position: 0% 50%; }  
        }  
  
        .sub {  
            margin-bottom: 20px;  
            font-size: 14px;  
            color: #A9A9A9;  
            letter-spacing: 0.15em;  
        }  
  
        .block {  
            background: #111;  
            padding: 16px;  
            border-radius: 10px;  
            font-size: 16px;  
            line-height: 1.6;  
            color: #E6E6E6;  
            border-left: 4px solid #A9A9A9;  
            text-align: left;  
        }  
  
        .block b {  
            color: #FFFFFF;  
        }  
          
        .footer {  
            margin-top: 18px;  
            font-size: 14px;  
            color: #888;  
        }  
    </style>  
</head>  
<body>  
    <div class="container">  
        <img class="avatar" src="https://web-bans.vercel.app/quality_restoration_20250918192910729.jpg" alt="Avatar">  
        <h1>TOUCH BY PAINZY.</h1>  
        <h1 class="palestine">STAND WITH PALESTINE</h1>  
        <div class="sub">Justice For Gaza ✊</div>  
        <div class="block">  
            <p><b>To The Admin of This Website:</b></br>
    Your silence is your consent.  
    We demand you stand for humanity.  
    Stop mocking Palestine, stop supporting occupation.  
    The world is watching, history will remember which side you chose.  
    Choose justice. Choose freedom.  
    Stand with the oppressed, or be exposed as an oppressor.</p>  
        </div>  
        <div class="footer">  
            #FreePalestine #StandWithGaza<br/>Humanity First 🌍  
        </div>  
    </div>  

    <audio id="bgMusic" src="https://a.top4top.io/m_3551p73n90.mp4"></audio>

    <script>
        const audio = document.getElementById("bgMusic");
        let isPlaying = false;

        document.body.addEventListener("click", () => {
            if (!isPlaying) {
                audio.play();
                isPlaying = true;
            }
        });
    </script>
</body>  
</html>