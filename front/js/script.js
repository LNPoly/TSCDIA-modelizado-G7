const header = document.querySelector('header');
const footer = document.querySelector('footer');

header.innerHTML=`
        <nav class="navbar navbar-dark bg-light2">
            <div class="container-fluid">
                <div class="col-1 text-center">
                    <img class="pna-logo" src="img/logoIA.png" alt="">
                </div>
                <div class="col-10 d-none d-lg-block text-center">
                    <ul class="nav ">
                        <li class="nav-item">
                            <a class="nav-link inscribite-link" aria-current="page" href="index.html">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"  class="bi bi-house-door-fill" viewBox="0 0 16 16">
                                    <path d="M6.5 14.5v-3.505c0-.245.25-.495.5-.495h2c.25 0 .5.25.5.5v3.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5"/>
                                </svg>
                            </a>
                        </li>
                            <li class="nav-item">
                            <a class="nav-link inscribite-link" href="./requisitos.html"></a>
                            </li>
                            <li class="nav-item">
                            <a class="nav-link inscribite-link" href="./requisitos.html"></a>
                        </li>
                    </ul>
                </div>
                <div class="col-1 text-end">
                </div>
                <div class="col-1 d-block d-lg-none text-end">
                    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                        <span class="navbar-toggler-icon"></span>
                    </button>
        
                    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                        <div class="offcanvas-header">
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                        </div>
                      
                    </div>
                </div>
            </div>
        </nav>
`



footer.innerHTML=`
<div class="row container-fluid footerplataforma p-0">
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link infomore active" data-bs-toggle="collapse" href="#multiCollapseExample1" role="button" aria-expanded="false" aria-controls="multiCollapseExample1">INFO +</a>
        </li>
    </ul>
    <div class="row container-fluid p-0 m-0">
        <div class="col p-0 infomore">
            <div class=" collapse multi-collapse" id="multiCollapseExample1">
                
                <div>
                    <hr class="col-md-3 col-0" style="color: rgba(0, 0, 0, 0); float: left;">
                    <hr class="col-md-6 col-12" style="color: #707070; float: left;">
                    <hr class="col-md-3 col-0" style="color: rgba(0, 0, 0, 0); float: left;">
                </div>
                
                <div class=p-0>
                    <hr class="col-md-3 col-0 mb-0" style="color: rgba(0, 0, 0, 0); float: left;">
                    <hr class="col-md-6 col-12 mb-0" style="color: #707070; float: left;">
                    <hr class="col-md-3 col-0 mb-0" style="color: rgba(0, 0, 0, 0); float: left;">
                </div>
                <div class="col-12 card card-body info p-0">
                <div class="container-fluid text-center">
                    <div class="row">
            
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>

    <p class="py-3">Diseño, Desarrollo Web y Contenidos | Nano & Co. | División web | tel:+5491162685499</p>
</div>
  `