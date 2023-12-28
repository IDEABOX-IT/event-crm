<template>
  <div class="bg-blue-100 min-h-screen flex items-center justify-center">
    <div class="bg-white shadow-lg p-8 rounded-lg">
      <h1 class="text-3xl font-semibold mb-4">Ticket QR Code Scanner</h1>
      <video ref="video" autoplay></video>
    </div>
  </div>
</template>

<script>
import {BrowserQRCodeReader} from '@zxing/library';

export default {
  data() {
    return {
      codeReader: null,
      videoElement: null,
    };
  },
  mounted() {
    this.setupQRCodeScanner();
  },
  methods: {
    async setupQRCodeScanner() {
      this.videoElement = this.$refs.video;
      this.codeReader = new BrowserQRCodeReader();
      let qrCodeText = '';
      try {
        await this.codeReader.decodeFromVideoDevice(undefined, this.videoElement, (result, error) => {
          if (result) {
            // Handle the QR code result
            qrCodeText = result.getText();
            if (qrCodeText) {
              window.open(`/checkTicketResult/${qrCodeText}/`, '_self');
            }
          } else if (error) {
          }
        });
      } catch (error) {
        // Handle any other errors during setup
        console.error('QR Code Scanning Error:', error);
      }
    },
  },
};
</script>
