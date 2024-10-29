import sympy 
import numpy as np
import matplotlib.pyplot as plt 
import ipywidgets

class xInteractiveFourier :  
    def __init__(self, MaxNumCoeffs) :
        self.t = sympy.Symbol('t', real = 'True', nonzero = 'True') 
        self.k = sympy.Symbol('k', real = 'True', nonzero=True, positive=True, integer=True)

        self.valuesArray     = []
        self.samplesArray    = []
        self.timeSamples     = []
        self.maxError        = []
        self.meanSquareError = []
        self.MaxNumCoeffs    = MaxNumCoeffs
        self.FigSize         = (12, 8)

        self.ErrorMax = np.zeros(self.MaxNumCoeffs)
        self.ErrorMSE = np.zeros(self.MaxNumCoeffs)
        return

    def complexFourierSeries(self, F0, Fk, Samples, rangeStart, rangeStop, mode = "fullInfo") :
        self.valuesArray     = np.zeros((self.MaxNumCoeffs, len(Samples)), dtype=complex)
        self.maxError        = np.zeros(self.MaxNumCoeffs)
        self.meanSquareError = np.zeros(self.MaxNumCoeffs)
        self.samplesArray    = Samples

        #calculate coeffs
        fkFunction = sympy.lambdify(self.k, Fk, 'numpy')
        a = np.linspace(1, self.MaxNumCoeffs-1, self.MaxNumCoeffs-1)
        coef = np.zeros(self.MaxNumCoeffs, dtype=complex)
        coef[0 ] = F0
        coef[1:] = fkFunction(a)

        if   mode == 'mag'  : coef = np.absolute(coef)
        elif mode == 'phase': coef = np.divide(coef,np.absolute(coef) + np.finfo(float).eps) # eps to avoid dividing by zero

        #calculate approx
        expFunction      = sympy.lambdify([self.t, self.k], sympy.exp((   sympy.I * 2 * self.k * sympy.pi * self.t)/(abs(rangeStop - rangeStart))))
        expFunctionConj  = sympy.lambdify([self.t, self.k], sympy.exp((-1*sympy.I * 2 * self.k * sympy.pi * self.t)/(abs(rangeStop - rangeStart))))
        self.timeSamples = np.linspace(rangeStart, rangeStop, len(Samples))

        currentStep = coef[0]*np.ones(len(Samples), dtype=complex)
        self.valuesArray[0,:] = currentStep
        for i in range(1, self.MaxNumCoeffs) :
            currentStep = np.add(currentStep, coef[i]*expFunction(self.timeSamples,i))                    #add coef
            currentStep = np.add(currentStep, coef[i].conjugate()*expFunctionConj(self.timeSamples,i))    #and conj coef 
            self.valuesArray[i,:] = currentStep
            self.maxError       [i] = (np.absolute(np.subtract(self.samplesArray, currentStep))).max()
            self.meanSquareError[i] = (np.real(np.square(np.subtract(self.samplesArray, currentStep)))).mean(axis=None)
        return
    
    def inteactiveDisplay(self, NumberOfCoefficients): 
        #display result
        fig, ax = plt.subplots(2, 2, figsize = self.FigSize)        
        fig.get_figwidth() #just to get rid of warning
        ax[0,0].plot(self.timeSamples,self.samplesArray                       , 'r', label = "org"  )
        ax[0,0].plot(self.timeSamples,np.real(self.valuesArray[NumberOfCoefficients,:]), 'g', label = "recon")
        ax[0,0].set_title("Original and reconstructed signal\n Reconstruction with {} coefs".format(NumberOfCoefficients))
        ax[0,0].legend(loc='lower left')

        ax[1,0].plot(self.timeSamples,np.subtract(self.samplesArray,np.real(self.valuesArray[NumberOfCoefficients,:])),'r')
        ax[1,0].set_title("Approximation error\n Reconstruction with {} coefs".format(NumberOfCoefficients))
        ax[1,0].grid()
        ax[1,0].set_ylim([-0.5, 0.5])

        ax[0,1].plot(np.arange(1,self.MaxNumCoeffs),self.maxError[1:],'m')
        ax[0,1].set_title("Maximal Error by number of Fourier coeficients. \nFor current numer of coeficients: {}".format(self.maxError[NumberOfCoefficients]))
        ax[0,1].set_xlabel("number of Fourier coeficients")
        ax[0,1].set_ylabel("maximal error")

        ax[1,1].plot(np.arange(1,self.MaxNumCoeffs),self.meanSquareError[1:],'k')
        ax[1,1].set_title("Mean Square Error by number of Fourier coeficients. \nFor current numer of coeficients: {}".format(self.meanSquareError[NumberOfCoefficients]))
        ax[1,1].set_xlabel("number of Fourier coeficients")
        ax[1,1].set_ylabel("mean square error")

        fig.tight_layout()
        plt.show()
        return 

    def createDemo(self, FigSize=(12, 8)):
        self.FigSize = FigSize
        return ipywidgets.interactive(self.inteactiveDisplay, NumberOfCoefficients=ipywidgets.IntSlider(min=1, max=self.MaxNumCoeffs-1, step=1, layout=ipywidgets.Layout(width="100%"), style={'description_width': 'initial'}))
        
