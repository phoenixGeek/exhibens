<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
  <title>PA Dashboard </title>
  <link rel="stylesheet" href="<?= base_url() ?>assets/dashboard/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
  <link rel="stylesheet" href="<?= base_url() ?>assets/dashboard/fonts/fontawesome-all.min.css">
  <link rel="stylesheet" href="<?= base_url() ?>assets/dashboard/fonts/font-awesome.min.css">
  <link rel="stylesheet" href="<?= base_url() ?>assets/dashboard/fonts/fontawesome5-overrides.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/dashboard/css/dashboard.css">
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/11.0.2/css/bootstrap-slider.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
  
  <style>
    .slider-horizontal {
      width: 100% !important;
      margin: 15px!important;
    }

    .slider-track-low,
    .slider-track-high {
      background-color: #c9d0fb !important;
    }

    .slider-selection {
      background-color: #7b81f9 !important;
      background-image: none;
    }

    #play-preview {
      padding: 0px 5px 5px 5px;
      border: 1px solid #d4d4d4;
    }

    #total-preview-player-wrapper {
      /* background-color: #717171; */
      min-height: 480px;
      height: 480px;
      margin: auto;
      border:1px solid #ccc;
      border-radius: 3px;
    }

    .edit-video-segment {
      font-size: 1rem;
      text-decoration: none !important;
    }

    #sample-player-img {
      margin: 70px;
    }

    .trim-video {
      position: relative;
      /* width: 480px;
    margin: 0; */
      text-align: center !important;
      margin: auto;
      height: 100%;
      width: auto;

    }

    .trim-video video {
      width: auto;
      height: 100%;
    }

    .video-controls {
      right: 0;
      left: 0;
      padding: 10px;
      position: absolute;
      bottom: 4px;
      transition: all 0.2s ease;
      background-image: linear-gradient(to bottom, rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.5));
    }

    .video-controls.hide {
      opacity: 0;
      pointer-events: none;
    }

    .video-progress {
      position: relative;
      height: 8.4px;
      margin-bottom: 10px;
    }

    #player-wrapper {
      height: 100%;
      width: auto;
      position: relative;
      overflow: hidden;
    }

    #player-controls {
      width: 100%;
      height: auto;
      background-color: rgba(80, 80, 80, 0.8);
      text-align: left;
      padding: 0px 15px 5px 15px;
      color: white;
      position: absolute;
      bottom: 0px;
      left: 0px;
    }

    #player-controls .control {
      font-size: 24px;
      color: white;
      margin-right: 15px;
      margin-top: -3px;
    }

    #player-controls .control:hover {
      color: yellow;
    }
    .bi::before{
      margin:3px 5px 3px 0px!important;
    }

    .card-body:hover{
      cursor: pointer;
    }
    .time-input{
      width: 35px!important;
      text-align:center;
      border-radius: 3px;
      border:1px solid #dedede;
    }
    /* Chrome, Safari, Edge, Opera */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }

    /* Firefox */
    input[type=number] {
      -moz-appearance: textfield;
    }

    .list-group .list-group-item {
      border-radius: 0;
      cursor: move;
    }

    .list-group .list-group-item:hover {
      background-color: #f7f7f7;
    }
    #full-duration-display-div,#full-duration-display-div1{
      height: 5px;
      background: #ccc;
      width: 100%;
      position: relative;
      border-radius: 5px;
    }
    #full-duration-display-div .progress,#full-duration-display-div1 .progress{
      position: absolute;
      width: 100%;
      top:0px;
      left: 0px;
      background: #b3cafb;
      border-left: 2px solid #848484;
      border-right: 2px solid #848484;
    }
    #segment-list .list-group-item:hover{
      background-color: #ccc;
      color:#7b81f9;
    }
    #segment-preview1{
      border: 1px solid #ccc;
      border-radius: 3px;
    }
    #step-2{
      display: none;
    }
    .add-new-video-segment{
      font-size: 14px!important;
      margin-top: 8px!important;
    }
    
  </style>

  <?php

  if (isset($stylesheet) && !empty($stylesheet)) {
    foreach ($stylesheet as $key => $style) {
      echo '<link rel="stylesheet" type="text/css" href="' . base_url() . 'assets/' . $style . '">';
      echo "\n";
    }
  }
  ?>
  <style>
    .video-slt {
      position: absolute;
      top: 10px;
      right: 10px;
      z-index: 1000;
    }
  </style>
</head>

<body>
  <div class="page-wrapper chiller-theme toggled"  id="page-top">
    <a id="show-sidebar" class="btn btn-sm btn-dark" href="#">
      <i class="fas fa-bars"></i>
    </a>
    <nav id="sidebar" class="sidebar-wrapper">
      <div class="sidebar-content">
        <div class="sidebar-brand">
          <a href="#">PA DASHBOARD</a>
          <div id="close-sidebar">
            <i class="fas fa-bars"></i>
          </div>
        </div>
        <div class="sidebar-header">
          <div class="user-pic">
            <img class="img-responsive img-rounded" src="<?= $current_user->thumb ?>" alt="User picture">
          </div>
          <div class="user-info">
            <span class="user-name">
              <?= $current_user->first_name ?>
              <strong> <?= $current_user->last_name ?></strong>
            </span>
            <span class="user-role"><?= $current_user->username ?></span>
            <span class="user-status">
              <i class="fa fa-circle"></i>
              <span>Online</span>
            </span>
          </div>
        </div>
        
        <!-- sidebar-search  -->
        <div class="sidebar-menu">
          <ul>

          <?php if ($is_admin) : ?>
              <li class="header-menu">
                <span>Website & CMS</span>
              </li>
              <li class="sidebar-dropdown">
                <a href="#">
                  <i class="fa fa-user"></i>
                  <span>User & Role</span>
                </a>
                <div class="sidebar-submenu">
                  <ul>
                    <li>
                      <a href="<?= base_url() ?>dashboard/users">Users Management</a>
                    </li>
                    <li>
                      <a href="<?= base_url() ?>dashboard/groups">Groups</a>
                    </li>
                    
                  </ul>
                </div>
              </li>
            <?php endif; ?> 

            <li class="header-menu">
              <span>Videos and Presentations</span>
            </li>
            <li class="sidebar-dropdown">
              <a href="#">
                <i class="fa fa-user"></i>
                <span>Media Library</span>
              </a>
              <div class="sidebar-submenu">
                <ul>
                  <li>
                    <a href="<?= base_url() ?>Pa_dashboard/videos_list">Videos</a>
                  </li>
                </ul>
              </div>
            </li>

            <li>
              <a href="<?= base_url() ?>Pa_dashboard/presentations">
                <i class="fa fa-user"></i>
                <span>Presentations</span>
              </a>
            </li>

            <li class="header-menu">
              <span>My profile</span>
            </li>
            <li class="sidebar-dropdown">
              <a href="#">
                <i class="fa fa-user-o"></i>
                <span>Profile</span>
              </a>
              <div class="sidebar-submenu">
                <ul>
                  <li>
                    <a href="<?= base_url() ?>profile">View</a>
                  </li>
                  <li>
                    <a href="<?= base_url() ?>profile/change-password">Change password</a>
                  </li>
                </ul>
              </div>
            </li>

            <li class="header-menu">
              <a href="<?= base_url() ?>logout">
                <i class="fa fa-sign-out"></i><span>Sign Out</span>
              </a>
            </li>
          </ul>
        </div>
        <!-- sidebar-menu  -->
      </div>

    </nav>
    <!-- sidebar-wrapper  -->
    <main class="page-content">