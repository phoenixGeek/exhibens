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
    

    #total-preview-player-wrapper {
      min-height: 480px;
      height: 480px;
      margin: auto;
      border:1px solid #ccc;
      border-radius: 3px;
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
      z-index:1000;
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
  </style>

</head>

<body>