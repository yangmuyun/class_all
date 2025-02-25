import cv2 as cv
import threading
import random
from time import sleep
import Arm_Lib
import smbus

from person_DiceGame import person_DiceGame
from robot_DiceGame import robot_DiceGame
from play_music import RoboticArmController
from code import QRCodeScanner
from dance import dance

import re
import requests
from datetime import datetime
from bs4 import BeautifulSoup

Arm = Arm_Lib.Arm_Device()
joints_0 = [90, 135, 20, 25, 90, 30]
Arm.Arm_serial_servo_write6_array(joints_0, 1000)

import time

bus = smbus.SMBus(1)

i2c_addr = 0x0f  # Speech recognition module address
asr_add_word_addr = 0x01  # Entry add address
asr_mode_addr = 0x02  # Recognition mode setting address, the value is 0-2, 0: cyclic recognition mode 1: password mode, 2: button mode, the default is cyclic detection
asr_rgb_addr = 0x03  # RGB lamp setting address, need to send two bits, the first one is directly the lamp number 1: blue 2: red 3: green
# The second byte is brightness 0-255, the larger the value, the higher the brightness
asr_rec_gain_addr = 0x04  # Identification sensitivity setting address, sensitivity can be set to 0x00-0x7f, the higher the value, the easier it is to detect but the easier it is to misjudge
# It is recommended to set the value to 0x40-0x55, the default value is 0x40
asr_clear_addr = 0x05  # Clear the operation address of the power-off cache, clear the cache area information before entering the information
asr_key_flag = 0x06  # Used in key mode, set the startup recognition mode
asr_voice_flag = 0x07  # Used to set whether to turn on the recognition result prompt sound
asr_result = 0x08  # Recognition result storage address
asr_buzzer = 0x09  # Buzzer control register, 1 bit is on, 0 bit is off
asr_num_cleck = 0x0a  # Check the number of entries
asr_vession = 0x0b  # firmware version number
asr_busy = 0x0c  # Busy and busy flag

i2c_speech_addr = 0x30  # ÓïÒô²¥±¨Ä£¿éµØÖ·
speech_date_head = 0xfd


# 天气爬虫
def weather():
    headers = {
        'User-Agent': 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.143 Safari/537.36',
    }
    res = requests.get('https://tianqi.moji.com/weather/china/zhejiang/xihu-district', headers=headers)
    soup = BeautifulSoup(res.text, "html.parser")

    temp = soup.find('div', class_='wea_weather clearfix').em.get_text()
    weather = soup.find('div', class_='wea_weather clearfix').b.get_text()
    sd = soup.find('div', class_='wea_about clearfix').span.get_text()
    sd_num = re.search(r'\d+', sd).group()
    wind = soup.find('div', class_='wea_about clearfix').em.get_text()
    aqi = soup.find('div', class_='wea_alert clearfix').em.get_text()
    aqi_num = re.search(r'\d+', aqi).group()
    info = soup.find('div', class_='wea_tips clearfix').em.get_text()

    # 格式化湿度和空气质量指数
    sd = '湿度百分之%s' % sd_num
    aqi = '空气质量指数%s' % aqi_num

    # 获取当前日期
    today = datetime.now().date().strftime('%Y年%m月%d日')

    # 构造返回的天气信息文本
    text = f'小主人好！今天是{today}，天气{weather}，温度{temp}摄氏度，{sd}，{wind}，{aqi}，{info}'

    return text


time_1 = 500
time_2 = 1000
time_sleep = 0.5


def I2C_WriteBytes(str_):
    global i2c_speech_addr
    for ch in str_:
        try:
            bus.write_byte(i2c_speech_addr, ch)
            time.sleep(0.01)
        except:
            print("write I2C error")


EncodingFormat_Type = {
    'GB2312': 0x00,
    'GBK': 0X01,
    'BIG5': 0x02,
    'UNICODE': 0x03
}


def Speech_text(str_, encoding_format):
    str_ = str_.encode('gb2312')
    size = len(str_) + 2
    DataHead = speech_date_head
    Length_HH = size >> 8
    Length_LL = size & 0x00ff
    Commond = 0x01
    EncodingFormat = encoding_format

    Date_Pack = [DataHead, Length_HH, Length_LL, Commond, EncodingFormat]

    I2C_WriteBytes(Date_Pack)

    I2C_WriteBytes(str_)


def SetBase(str_):
    str_ = str_.encode('gb2312')
    size = len(str_) + 2

    DataHead = speech_date_head
    Length_HH = size >> 8
    Length_LL = size & 0x00ff
    Commond = 0x01
    EncodingFormat = 0x00

    Date_Pack = [DataHead, Length_HH, Length_LL, Commond, EncodingFormat]

    I2C_WriteBytes(Date_Pack)

    I2C_WriteBytes(str_)


def TextCtrl(ch, num):
    if num != -1:
        str_T = '[' + ch + str(num) + ']'
        SetBase(str_T)
    else:
        str_T = '[' + ch + ']'
        SetBase(str_T)


ChipStatus_Type = {
    'ChipStatus_InitSuccessful': 0x4A,
    'ChipStatus_CorrectCommand': 0x41,
    'ChipStatus_ErrorCommand': 0x45,
    'ChipStatus_Busy': 0x4E,
    'ChipStatus_Idle': 0x4F
}


def GetChipStatus():
    global i2c_speech_addr
    AskState = [0xfd, 0x00, 0x01, 0x21]
    try:
        I2C_WriteBytes(AskState)
        time.sleep(0.05)
    except:
        print("I2CRead_Write error")

    try:
        Read_result = bus.read_byte(i2c_speech_addr)
        return Read_result
    except:
        print("I2CRead error")


Style_Type = {
    'Style_Single': 0,
    'Style_Continue': 1
}


def SetStyle(num):
    TextCtrl('f', num)
    while GetChipStatus() != ChipStatus_Type['ChipStatus_Idle']:
        time.sleep(0.002)


Language_Type = {
    'Language_Auto': 0,
    'Language_Chinese': 1,
    'Language_English': 2
}


def SetLanguage(num):
    TextCtrl('g', num)
    while GetChipStatus() != ChipStatus_Type['ChipStatus_Idle']:
        time.sleep(0.002)


Articulation_Type = {
    'Articulation_Auto': 0,
    'Articulation_Letter': 1,
    'Articulation_Word': 2
}


def SetArticulation(num):
    TextCtrl('h', num)
    while GetChipStatus() != ChipStatus_Type['ChipStatus_Idle']:
        time.sleep(0.002)


Spell_Type = {
    'Spell_Disable': 0,
    'Spell_Enable': 1
}


def SetSpell(num):
    TextCtrl('i', num)
    while GetChipStatus() != ChipStatus_Type['ChipStatus_Idle']:
        time.sleep(0.002)


Reader_Type = {
    'Reader_XiaoYan': 3,
    'Reader_XuJiu': 51,
    'Reader_XuDuo': 52,
    'Reader_XiaoPing': 53,
    'Reader_DonaldDuck': 54,
    'Reader_XuXiaoBao': 55
}


def SetReader(num):
    TextCtrl('m', num)
    while GetChipStatus() != ChipStatus_Type['ChipStatus_Idle']:
        time.sleep(0.002)


NumberHandle_Type = {
    'NumberHandle_Auto': 0,
    'NumberHandle_Number': 1,
    'NumberHandle_Value': 2
}


def SetNumberHandle(num):
    TextCtrl('n', num)
    while GetChipStatus() != ChipStatus_Type['ChipStatus_Idle']:
        time.sleep(0.002)


ZeroPronunciation_Type = {
    'ZeroPronunciation_Zero': 0,
    'ZeroPronunciation_O': 1
}


def SetZeroPronunciation(num):
    TextCtrl('o', num)
    while GetChipStatus() != ChipStatus_Type['ChipStatus_Idle']:
        time.sleep(0.002)


NamePronunciation_Type = {
    'NamePronunciation_Auto': 0,
    'NamePronunciation_Constraint': 1
}


def SetNamePronunciation(num):
    TextCtrl('r', num)
    while GetChipStatus() != ChipStatus_Type['ChipStatus_Idle']:
        time.sleep(0.002)


def SetSpeed(speed):
    TextCtrl('s', speed)
    while GetChipStatus() != ChipStatus_Type['ChipStatus_Idle']:
        time.sleep(0.002)


def SetIntonation(intonation):
    TextCtrl('t', intonation)
    while GetChipStatus() != ChipStatus_Type['ChipStatus_Idle']:
        time.sleep(0.002)


def SetVolume(volume):
    TextCtrl('v', volume)
    while GetChipStatus() != ChipStatus_Type['ChipStatus_Idle']:
        time.sleep(0.002)


OnePronunciation_Type = {
    'OnePronunciation_Yao': 0,
    'OnePronunciation_Yi': 1
}


def SetOnePronunciation(num):
    TextCtrl('y', num)
    while GetChipStatus() != ChipStatus_Type['ChipStatus_Idle']:
        time.sleep(0.002)


Rhythm_Type = {
    'Rhythm_Diasble': 0,
    'Rhythm_Enable': 1
}


def SetRhythm(num):
    TextCtrl('z', num)
    while GetChipStatus() != ChipStatus_Type['ChipStatus_Idle']:
        time.sleep(0.002)


def SetRestoreDefault():
    TextCtrl('d', -1)
    while GetChipStatus() != ChipStatus_Type['ChipStatus_Idle']:
        time.sleep(0.002)


# Write entry
def AsrAddWords(idnum, str):
    global i2c_addr
    global asr_add_word_addr
    words = []
    words.append(asr_add_word_addr)
    words.append(len(str) + 2)
    words.append(idnum)
    for alond_word in str:
        words.append(ord(alond_word))
    words.append(0)
    print(words)
    for date in words:
        bus.write_byte(i2c_addr, date)
        time.sleep(0.03)


# Set RGB
def RGBSet(R, G, B):
    global i2c_addr
    global asr_rgb_addr
    date = []
    date.append(R)
    date.append(G)
    date.append(B)
    print(date)
    bus.write_i2c_block_data(i2c_addr, asr_rgb_addr, date)


# Read result
def I2CReadByte(reg):
    global i2c_addr
    bus.write_byte(i2c_addr, reg)
    time.sleep(0.05)
    Read_result = bus.read_byte(i2c_addr)
    return Read_result


# Wait busy
def Busy_Wait():
    busy = 255
    while busy != 0:
        busy = I2CReadByte(asr_busy)
        print(asr_busy)


'''
The mode and phrase have the function of power-down save, if there is no modification after the first entry, you can change 1 to 0
'''
cleck = 1

if 1:
    bus.write_byte_data(i2c_addr, asr_clear_addr, 0x40)  # Clear the power-down buffer area
    Busy_Wait()  # Wait for the module to be free
    print("Cache cleared")
    bus.write_byte_data(i2c_addr, asr_mode_addr, 1)
    Busy_Wait()
    print("The mode is set")
    AsrAddWords(0, "xiao hua")
    Busy_Wait()
    AsrAddWords(1, "you xi")
    Busy_Wait()
    AsrAddWords(2, "tian qi")
    Busy_Wait()
    AsrAddWords(3, "can zhan")
    Busy_Wait()
    AsrAddWords(4, "guan zhan")
    Busy_Wait()
    AsrAddWords(5, "yan zou")
    Busy_Wait()
    AsrAddWords(6, "tiao wu")
    Busy_Wait()
    AsrAddWords(7, "gu shi")
    Busy_Wait()
    AsrAddWords(8, "qu xiao")
    Busy_Wait()
    while cleck != 9:
        cleck = I2CReadByte(asr_num_cleck)
        print(cleck)

bus.write_byte_data(i2c_addr, asr_rec_gain_addr, 0x40)  # Set the sensitivity, the recommended value is 0x40-0x55
bus.write_byte_data(i2c_addr, asr_voice_flag, 1)  # Set switch sound
bus.write_byte_data(i2c_addr, asr_buzzer, 1)  # buzzer
RGBSet(255, 255, 255)
time.sleep(1)
RGBSet(0, 0, 0)
bus.write_byte_data(i2c_addr, asr_buzzer, 0)  # buzzer

SetReader(Reader_Type["Reader_XiaoYan"])
SetVolume(20)
Speech_text("欢迎来到你的专属智能乐园，我是亲爱的小花。你想要做什么。", EncodingFormat_Type["GB2312"])
while GetChipStatus() != ChipStatus_Type['ChipStatus_Idle']:  # 等待当前语句播报结束
    time.sleep(0.1)


def main():
    global HSV_learning, model
    # 打开摄像头
    capture = cv.VideoCapture(1)
    capture.set(3, 640)
    capture.set(4, 480)
    capture.set(5, 30)  # 设置帧率
    #     当摄像头正常打开的情况下循环执行
    while capture.isOpened():
        #    while True:
        try:
            result = I2CReadByte(asr_result)
            time.sleep(0.01)
            if result == 1:
                Speech_text("欢迎来到小花的飞行棋世界，请问小主人是想自己玩还是和小花一起玩？",
                            EncodingFormat_Type["GB2312"])
                while GetChipStatus() != ChipStatus_Type['ChipStatus_Idle']:  # 等待当前语句播报结束
                    time.sleep(0.1)
            elif result == 2:
                text = weather()
                print(text)
                Speech_text(text, EncodingFormat_Type["GB2312"])
                while GetChipStatus() != ChipStatus_Type['ChipStatus_Idle']:
                    time.sleep(0.1)
            elif result == 3:
                Speech_text("即将进行人机对战，请小主人做好准备",
                            EncodingFormat_Type["GB2312"])
                while GetChipStatus() != ChipStatus_Type['ChipStatus_Idle']:  # 等待当前语句播报结束
                    time.sleep(0.1)
                #                 person_DiceGame().start_person_game()
                pd = person_DiceGame()
                pd.cap = capture
                pd.start_person_game()
            elif result == 4:
                Speech_text("即将进行机器对战，请小主人观战",
                            EncodingFormat_Type["GB2312"])
                while GetChipStatus() != ChipStatus_Type['ChipStatus_Idle']:  # 等待当前语句播报结束
                    time.sleep(0.1)
                #                 robot_DiceGame().start_robot_game()
                rd = robot_DiceGame()
                rd.cap = capture
                rd.start_robot_game()
            elif result == 5:
                Speech_text("小花即将变成演奏大师，请小主人准备聆听",
                            EncodingFormat_Type["GB2312"])
                while GetChipStatus() != ChipStatus_Type['ChipStatus_Idle']:  # 等待当前语句播报结束
                    time.sleep(0.1)
                time.sleep(10)
                RoboticArmController().main_game()
            elif result == 6:
                Speech_text("小花就要开始跳舞啦，请小主人给小花一个手势，然后搬好小板凳准备观看哦",
                            EncodingFormat_Type["GB2312"])
                while GetChipStatus() != ChipStatus_Type['ChipStatus_Idle']:  # 等待当前语句播报结束
                    time.sleep(0.1)
                #                 dance().start()
                tw = dance()
                tw.cap = capture
                tw.start()
            elif result == 7:
                Speech_text("小花要开始说花花的笑话了，请小主人不要吃东西哦",
                            EncodingFormat_Type["GB2312"])
                while GetChipStatus() != ChipStatus_Type['ChipStatus_Idle']:  # 等待当前语句播报结束
                    time.sleep(0.1)
                #                 QRCodeScanner().capture_qr_codes()
                story = QRCodeScanner()
                story.cap = capture
                cap.capture_qr_codes()
            elif result == 7:
                # result = 255
                text = weather()
                Speech_text("ok", EncodingFormat_Type["GB2312"])
                while GetChipStatus() != ChipStatus_Type['ChipStatus_Idle']:
                    time.sleep(0.1)
                cv.destroyAllWindows()
                # del Arm
                # del bus
                print(" Program closed! ")
                pass
                break

        except KeyboardInterrupt:
            capture.release()
            del Arm
            del bus

            print(" Program closed! ")
            pass


# def main():
#     while True:
#         result = I2CReadByte(asr_result)
#         if result == 1:
#             print(1)

#
# display(controls_box,output)
# threading.Thread(target=camera, ).start()

try:
    main()
except KeyboardInterrupt:
    capture.release()
#     del Arm
#     del bus

#     print(" Program closed! ")
#     pass
