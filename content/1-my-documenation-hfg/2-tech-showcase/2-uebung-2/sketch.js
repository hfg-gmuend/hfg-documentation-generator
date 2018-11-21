// works only in the p5 desktop app, or if you setup a local webserver
// http://p5js.org/examples/examples/Sound_Frequency_Spectrum.php
// http://p5js.org/reference/#/p5.FFT

var mic;
var fft;

function setup() {
  createCanvas(640, 256);
  noFill();

  mic = new p5.AudioIn();
  mic.start();
  fft = new p5.FFT(0.8, 512);
  fft.setInput(mic);
}

function draw() {
  background(200);

  var spectrum = fft.analyze();
  beginShape();
  for (i = 0; i < spectrum.length; i++) {
    vertex(i, map(spectrum[i], 0, 255, height, 0));
  }
  endShape();

  var energy = fft.getEnergy("lowMid");
  // "bass", "lowMid", "mid", "highMid", "treble"
  // returns a range between 0 and 255
  // note: analyze() must be called prior to getEnergy()
  strokeWeight(3);
  var y = map(energy, 0, 255, height, 0);
  line(0,y,width,y);
}
