<div class="fixed inset-0 z-0 pointer-events-none overflow-hidden opacity-15 w-screen h-screen">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 800" class="absolute w-full h-full object-cover"
        preserveAspectRatio="xMidYMid slice">
        <defs>
            <linearGradient id="skyGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                <stop offset="0%" style="stop-color:#4A90E2;stop-opacity:0.2" />
                <stop offset="100%" style="stop-color:#1C2056;stop-opacity:0.1" />
            </linearGradient>
        </defs>
        <rect width="100%" height="100%" fill="url(#skyGradient)" />

        <!-- Far Mountains -->
        <path fill="#1C2056" opacity="0.3" d="M0,800 
                L0,400 
                L200,500 
                L400,350 
                L600,450 
                L800,380 
                L1000,420 
                L1200,350 
                L1440,400 
                L1440,800 Z" />

        <!-- Middle Mountains -->
        <path fill="#1C2056" opacity="0.4" d="M0,800 
                L0,500 
                L240,600 
                L480,450 
                L720,550 
                L960,480 
                L1200,550 
                L1440,500 
                L1440,800 Z" />

        <!-- Front Mountains -->
        <path fill="#1C2056" opacity="0.5" d="M0,800 
                L0,600 
                L300,650 
                L600,550 
                L900,650 
                L1200,600 
                L1440,650 
                L1440,800 Z" />

        <!-- Snow Caps -->
        <path fill="white" opacity="0.4" d="M200,500 
                L240,480 
                L280,500 
                M600,450 
                L640,430 
                L680,450 
                M1000,420 
                L1040,400 
                L1080,420" />

        <!-- Clouds -->
        <g opacity="0.6">
            <!-- Cloud 1 -->
            <path fill="white" d="M100,200 
                    a20,20 0 0,1 40,0
                    a20,20 0 0,1 40,0
                    a20,20 0 0,1 40,0
                    q0,20 -60,20
                    q-60,0 -60,-20" />

            <!-- Cloud 2 -->
            <path fill="white" d="M800,150 
                    a25,25 0 0,1 50,0
                    a25,25 0 0,1 50,0
                    a25,25 0 0,1 50,0
                    q0,25 -75,25
                    q-75,0 -75,-25" />

            <!-- Cloud 3 -->
            <path fill="white" d="M400,100 
                    a15,15 0 0,1 30,0
                    a15,15 0 0,1 30,0
                    a15,15 0 0,1 30,0
                    q0,15 -45,15
                    q-45,0 -45,-15" />
        </g>

        <!-- Trees on Mountains -->
        <g opacity="0.6">
            <!-- Tree Group 1 -->
            <g transform="translate(250, 600)">
                <path fill="#1a472a" d="M0,-40 L10,0 L-10,0 Z" />
                <path fill="#1a472a" d="M0,-55 L15,-15 L-15,-15 Z" />
                <rect x="-2" y="0" width="4" height="10" fill="#5d4037" />
            </g>

            <!-- Tree Group 2 -->
            <g transform="translate(700, 580)">
                <path fill="#1a472a" d="M0,-55 L15,-15 L-15,-15 Z" />
                <path fill="#1a472a" d="M0,-70 L20,-30 L-20,-30 Z" />
                <rect x="-3" y="-15" width="6" height="15" fill="#5d4037" />
            </g>

            <!-- Tree Group 3 -->
            <g transform="translate(1100, 620)">
                <path fill="#1a472a" d="M0,-48 L12,-8 L-12,-8 Z" />
                <path fill="#1a472a" d="M0,-63 L17,-23 L-17,-23 Z" />
                <rect x="-2.5" y="-8" width="5" height="12" fill="#5d4037" />
            </g>
        </g>

        <!-- Birds -->
        <g opacity="0.4">
            <path fill="#1C2056" d="M300,200 q5,-5 10,0 q5,5 10,0 q5,-5 10,0" />
            <path fill="#1C2056" d="M850,150 q5,-5 10,0 q5,5 10,0 q5,-5 10,0" />
            <path fill="#1C2056" d="M600,180 q5,-5 10,0 q5,5 10,0 q5,-5 10,0" />
        </g>
    </svg>
</div>