//+------------------------------------------------------------------+  
//|                                                      XAUUSD_Bot.mq5 |  
//|                        Copyright 2023, Your Name                   |  
//|                                             https://www.yourlink.com |  
//+------------------------------------------------------------------+  
#property copyright "Copyright 2023, Your Name"  
#property link      "https://www.yourlink.com"  
#property version   "1.00"  
#property strict  
// Definición de parámetros  
input double riskPercentage = 1.0;  // Porcentaje de capital a arriesgar por operación  
input int atrPeriod = 14;           // Periodo para calcular el ATR  
input int fibonacciPeriod = 20;     // Periodo para calcular Fibonacci  
// Variables globales  
double movingAverageLong, movingAverageShort;  
double rsiValue;  
double macdMain, macdSignal;  
double bollingerUpper, bollingerLower;  
double adxValue;  
double ichimokuKijun;  
double ichimokuTenkan;  
double atrValue;  
double fiboLevels[5];  
//+------------------------------------------------------------------+  
//| Expert initialization function                                   |  
//+------------------------------------------------------------------+  
int OnInit()  
  {  
   Print("XAUUSD Bot iniciado.");  
   return(INIT_SUCCEEDED);  
  }  
//+------------------------------------------------------------------+  
//| Expert deinitialization function                                 |  
//+------------------------------------------------------------------+  
void OnDeinit(const int reason)  
  {  
   Print("XAUUSD Bot detenido.");  
  }  
//+------------------------------------------------------------------+  
//| Expert tick function                                             |  
//+------------------------------------------------------------------+  
void OnTick()  
  {  
   PerformTechnicalAnalysis();  
   CalculateFibonacciLevels();  
   ManagePositions();  
  }  
//+------------------------------------------------------------------+  
//| Función para realizar el análisis técnico                        |  
//+------------------------------------------------------------------+  
void PerformTechnicalAnalysis()  
{  
   movingAverageLong = iMA(_Symbol, PERIOD_D1, 200, 0, MODE_SMA, PRICE_CLOSE);  
   movingAverageShort = iMA(_Symbol, PERIOD_D1, 50, 0, MODE_SMA, PRICE_CLOSE);  
   rsiValue = iRSI(_Symbol, PERIOD_H4, 14, PRICE_CLOSE);  
   macdMain = iMACD(_Symbol, PERIOD_H4, 12, 26, 9, PRICE_CLOSE, MODE_MAIN);  
   macdSignal = iMACD(_Symbol, PERIOD_H4, 12, 26, 9, PRICE_CLOSE, MODE_SIGNAL);  
   bollingerUpper = iBands(_Symbol, PERIOD_H4, 20, 2, 0, PRICE_CLOSE, BAND_UPPER);  
   bollingerLower = iBands(_Symbol, PERIOD_H4, 20, 2, 0, PRICE_CLOSE, BAND_LOWER);  
   adxValue = iADX(_Symbol, PERIOD_H4, 14, ADX_MAIN);  
   ichimokuKijun = iIchimoku(_Symbol, PERIOD_D1, 9, 26, 52, ICHIMOKU_KIJUN);  
   ichimokuTenkan = iIchimoku(_Symbol, PERIOD_D1, 9, 26, 52, ICHIMOKU_TENKAN);  
   atrValue = iATR(_Symbol, PERIOD_H4, atrPeriod);  // Calcular ATR  
}
//+------------------------------------------------------------------+  
//| Función para calcular niveles de Fibonacci                       |  
//+------------------------------------------------------------------+  
void CalculateFibonacciLevels()  
  {  
   double high = iHigh(_Symbol, PERIOD_D1, iHighest(_Symbol, PERIOD_D1, MODE_HIGH, fibonacciPeriod));  
   double low = iLow(_Symbol, PERIOD_D1, iLowest(_Symbol, PERIOD_D1, MODE_LOW, fibonacciPeriod));  
   double range = high - low;  
   fiboLevels[0] = high - range * 0.236;  
   fiboLevels[1] = high - range * 0.382;  
   fiboLevels[2] = high - range * 0.500;  
   fiboLevels[3] = high - range * 0.618;  
   fiboLevels[4] = high - range * 1.000;  
  }  
//+------------------------------------------------------------------+  
//| Función para gestionar posiciones                                |  
//+------------------------------------------------------------------+  
void ManagePositions()  
  {  
   double accountBalance = AccountInfoDouble(ACCOUNT_BALANCE);  
   double riskAmount = accountBalance * (riskPercentage / 100.0);  
   double lotSize = CalculateLotSize(riskAmount, atrValue);  
   double ask = SymbolInfoDouble(_Symbol, SYMBOL_ASK);  
   double bid = SymbolInfoDouble(_Symbol, SYMBOL_BID);  
   // Confirmar tendencia fuerte con ADX  
   if (adxValue > 25)  
     {  
      // Lógica de compra mejorada  
      if (rsiValue < 30 && ask < movingAverageLong && macdMain > macdSignal && movingAverageShort > movingAverageLong && ask > fiboLevels[1])  
        {  
         double sl = ask - atrValue;  
         double tp = ask + (atrValue * 2);  
         MqlTradeRequest request; 
         MqlTradeResult result; 
         ZeroMemory(request); 
         request.action = TRADE_ACTION_DEAL; 
         request.symbol = _Symbol; 
         request.volume = lotSize; 
         request.type = ORDER_TYPE_BUY; 
         request.price = ask; 
         request.sl = sl; 
         request.tp = tp; 
         request.deviation = 2; 
         request.magic = 0; 
         request.comment = "Compra XAUUSD"; 
         if(!OrderSend(request, result))
         {
            Print("Error al enviar la orden de compra: ", GetLastError());
         }
        }  
      // Lógica de venta mejorada  
      if (rsiValue > 70 && bid > movingAverageLong && macdMain < macdSignal && movingAverageShort < movingAverageLong && bid < fiboLevels[3])  
        {  
         double sl = bid + atrValue;  
         double tp = bid - (atrValue * 2);  
         MqlTradeRequest request; 
         MqlTradeResult result; 
         ZeroMemory(request); 
         request.action = TRADE_ACTION_DEAL; 
         request.symbol = _Symbol; 
         request.volume = lotSize; 
         request.type = ORDER_TYPE_SELL; 
         request.price = bid; 
         request.sl = sl; 
         request.tp = tp; 
         request.deviation = 2; 
         request.magic = 0; 
         request.comment = "Venta XAUUSD"; 
         if(!OrderSend(request, result))
         {
            Print("Error al enviar la orden de venta: ", GetLastError());
         }
        }  
     }  
  }  
//+------------------------------------------------------------------+  
//| Función para calcular el tamaño del lote                         |  
//+------------------------------------------------------------------+  
double CalculateLotSize(double riskAmount, double atrValue)  
  {  
   double tickValue = SymbolInfoDouble(_Symbol, SYMBOL_TRADE_TICK_VALUE);  
   double lotSize = riskAmount / (atrValue * tickValue);  
   return lotSize;  
  }  
//+------------------------------------------------------------------+