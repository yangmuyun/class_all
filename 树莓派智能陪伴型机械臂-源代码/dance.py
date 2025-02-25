import cv2
import time
import demjson
import pygame
from aip import AipBodyAnalysis
from PIL import Image, ImageDraw, ImageFont
import numpy as np
import ipywidgets.widgets as widgets
import random
from Arm_Lib import Arm_Device
from IPython.display import display
from speak import speak

Arm = Arm_Device()


class dance:
    def __init__(self) -> None:
        # Replace with your own Baidu AI credentials
        APP_ID = '93097451'
        API_KEY = '3om56y5ScOOn36Em2UJXbMZ4'
        SECRET_KEY = '26VMq5kQqjdfNfkalZVNq6pjdF5yYNcs'

        self.client = AipBodyAnalysis(APP_ID, API_KEY, SECRET_KEY)

        # Initialize camera
        # self.g_camera = cv2.VideoCapture(1)

        # Mapping of gesture class names to text descriptions
        self.hand = {'One': '数字 1', 'Two': '数字 2', 'Three': '数字 3', 'Four': '数字 4',
                     'Five': '数字 5', 'Six': '数字 6', 'Seven': '数字 7',
                     'Eight': '数字 8', 'Nine': '数字 9', 'Fist': '拳头', 'Ok': 'OK',
                     'Prayer': '祈祷', 'Congratulation': '作揖', 'Honour': '作别',
                     'Heart_single': '比心心', 'Thumb_up': '点赞', 'Thumb_down': 'Diss',
                     'ILY': '我爱你', 'Palm_up': '掌心向上', 'Heart_1': '双手比心 1',
                     'Heart_2': '双手比心 2', 'Heart_3': '双手比心 3', 'Rock': 'Rock',
                     'Insult': '竖中指', 'Face': '脸'}

        # Define time intervals
        self.times = 1
        self.time_1 = 500
        self.time_2 = 1000
        self.time_sleep = 1

        self.cap = None

    Reader_Type = {
        'Reader_XiaoYan': 3,  # 为 3，设置发音人为小燕(女声, 推荐发音人)
        'Reader_XuJiu': 51,  # 为 51，设置发音人为许久(男声, 推荐发音人)
        'Reader_XuDuo': 52,  # 为 52，设置发音人为许多(男声)
        'Reader_XiaoPing': 53,  # 为 53，设置发音人为小萍(女声
        'Reader_DonaldDuck': 54,  # 为 54，设置发音人为唐老鸭(效果器)
        'Reader_XuXiaoBao': 55  # 为 55，设置发音人为许小宝(女童声)
    }  # 选择发音人 [m?]'

    # Function to convert BGR image to JPEG format
    def bgr8_to_jpeg(self, image):
        image_rgb = cv2.cvtColor(image, cv2.COLOR_BGR2RGB)
        _, jpeg = cv2.imencode('.jpg', image_rgb)
        return jpeg.tobytes()

    # 定义转换显示中文函数
    def cv2ImgAddText(self, img, text, left, top, textColor=(0, 255, 0), textSize=20):
        if (isinstance(img, np.ndarray)):  # 判断是否OpenCV图片类型
            img = Image.fromarray(cv2.cvtColor(img, cv2.COLOR_BGR2RGB))
        # 创建一个可以在给定图像上绘图的对象
        draw = ImageDraw.Draw(img)
        # 字体的格式
        fontStyle = ImageFont.truetype(
            "SettingsIcons.ttf", textSize, encoding="utf-8")
        # 绘制文本
        draw.text((left, top), text, textColor, font=fontStyle)
        # 转换回OpenCV格式
        return cv2.cvtColor(np.asarray(img), cv2.COLOR_RGB2BGR)

    def start(self):
        myspeak = speak()
        #         g_camera = cv2.VideoCapture(1)
        g_camera = self.cap
        # 打开摄像头
        g_camera.set(3, 640)
        g_camera.set(4, 480)
        g_camera.set(5, 30)  # Frame rate
        g_camera.set(cv2.CAP_PROP_FOURCC, cv2.VideoWriter.fourcc('M', 'J', 'P', 'G'))
        g_camera.set(cv2.CAP_PROP_BRIGHTNESS, 20)  # Brightness
        g_camera.set(cv2.CAP_PROP_CONTRAST, 50)  # Contrast
        g_camera.set(cv2.CAP_PROP_EXPOSURE, 100)  # Exposure
        myspeak.SetReader(myspeak.Reader_Type["Reader_XiaoYan"])  # 选择播音人许多
        myspeak.Speech_text("开始识别", myspeak.EncodingFormat_Type["GB2312"])
        while myspeak.GetChipStatus() != myspeak.ChipStatus_Type['ChipStatus_Idle']:  # 等待当前语句播报结束
            time.sleep(0.1)
        print("识别中")
        time.sleep(3)
        flag = True
        try:
            while flag:

                Arm.Arm_serial_servo_write6(90, 135, 20, 25, 90, 30, 1000)

                ret, frame = g_camera.read()
                if not ret:
                    print("Error: Could not read frame.")
                time.sleep(3)

                # raw = str(self.client.gesture(self.image_widget.value))
                # text = demjson.decode(raw)

                self.image_widget = widgets.Image(format='jpeg', width=600, height=500)
                display(self.image_widget)

                # Capture frame from camera

                # Convert frame to JPEG format for processing
                frame_jpeg = self.bgr8_to_jpeg(frame)

                # Perform gesture recognition
                gesture_result = self.client.gesture(frame_jpeg)
                raw_result = str(gesture_result)
                parsed_result = demjson.decode(raw_result)
                try:
                    res = parsed_result['result'][0]['classname']
                except:
                    print('识别结果：未能识别到手势')
                    img = frame
                else:
                    print('识别结果：' + self.hand.get(res, '未知手势'))

                    if res == "Thumb_up":

                        print('识别结果' + self.hand.get(res, '未知手势'))
                        myspeak.SetReader(myspeak.Reader_Type["Reader_XiaoYan"])  # 选择播音人许多
                        myspeak.Speech_text("小花看到你比的大拇哥啦", myspeak.EncodingFormat_Type["GB2312"])
                        while myspeak.GetChipStatus() != myspeak.ChipStatus_Type['ChipStatus_Idle']:  # 等待当前语句播报结束
                            time.sleep(0.1)
                        flag = False

                        Arm.Arm_serial_servo_write(6, 90, self.time_2)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(1, 90, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(1, 90, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(5, 90, self.time_1)
                        time.sleep(self.time_sleep)

                        Arm.Arm_serial_servo_write(3, 90, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(4, 90, self.time_1)
                        time.sleep(self.time_sleep)

                        Arm.Arm_serial_servo_write(2, 180 - 120, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(3, 120, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(4, 60, self.time_1)
                        time.sleep(self.time_sleep)

                        Arm.Arm_serial_servo_write(2, 180 - 135, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(3, 135, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(4, 45, self.time_1)
                        time.sleep(self.time_sleep)

                        Arm.Arm_serial_servo_write(2, 180 - 120, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(3, 120, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(4, 60, self.time_1)
                        time.sleep(self.time_sleep)

                        Arm.Arm_serial_servo_write(2, 90, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(3, 90, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(4, 90, self.time_1)
                        time.sleep(self.time_sleep)

                        Arm.Arm_serial_servo_write(2, 180 - 80, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(3, 80, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(4, 80, self.time_1)
                        time.sleep(self.time_sleep)

                        Arm.Arm_serial_servo_write(2, 180 - 60, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(3, 60, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(4, 60, self.time_1)
                        time.sleep(self.time_sleep)

                        Arm.Arm_serial_servo_write(2, 180 - 45, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(3, 45, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(4, 45, self.time_1)
                        time.sleep(self.time_sleep)

                        Arm.Arm_serial_servo_write(2, 90, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(3, 90, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(4, 90, self.time_1)
                        time.sleep(self.times)
                        time.sleep(self.time_sleep)

                        Arm.Arm_serial_servo_write(4, 20, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(6, 150, self.time_1)
                        time.sleep(self.times)
                        time.sleep(self.time_sleep)

                        Arm.Arm_serial_servo_write(4, 90, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(6, 90, self.time_1)
                        time.sleep(self.time_sleep)

                        Arm.Arm_serial_servo_write(4, 20, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(6, 150, self.time_1)
                        time.sleep(self.time_sleep)

                        Arm.Arm_serial_servo_write(4, 90, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(6, 90, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(1, 0, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(5, 0, self.time_1)
                        time.sleep(self.time_sleep)

                        Arm.Arm_serial_servo_write(3, 180, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(4, 0, self.time_1)
                        time.sleep(self.time_sleep)

                        Arm.Arm_serial_servo_write(6, 180, self.time_1)
                        time.sleep(self.time_sleep)

                        Arm.Arm_serial_servo_write(6, 0, self.time_2)
                        time.sleep(self.time_sleep)

                        print("well done")
                    elif res == "Fist":
                        print('识别结果' + self.hand.get(res, '未知手势'))
                        myspeak.SetReader(myspeak.Reader_Type["Reader_XiaoYan"])  # 选择播音人许多
                        myspeak.Speech_text("小花看到你比的拳头啦", myspeak.EncodingFormat_Type["GB2312"])
                        while myspeak.GetChipStatus() != myspeak.ChipStatus_Type['ChipStatus_Idle']:  # 等待当前语句播报结束
                            time.sleep(0.1)
                        flag = False

                        Arm.Arm_serial_servo_write(2, 180 - 60, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(3, 60, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(4, 60, self.time_1)
                        time.sleep(self.time_sleep)

                        Arm.Arm_serial_servo_write(2, 180 - 45, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(3, 45, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(4, 45, self.time_1)
                        time.sleep(self.time_sleep)

                        Arm.Arm_serial_servo_write(2, 90, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(3, 90, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(4, 90, self.time_1)
                        time.sleep(self.times)
                        time.sleep(self.time_sleep)

                        Arm.Arm_serial_servo_write(2, 180 - 120, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(3, 120, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(4, 60, self.time_1)
                        time.sleep(self.time_sleep)

                        Arm.Arm_serial_servo_write(2, 180 - 135, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(3, 135, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(4, 45, self.time_1)
                        time.sleep(self.time_sleep)

                        Arm.Arm_serial_servo_write(2, 180 - 120, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(3, 120, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(4, 60, self.time_1)
                        time.sleep(self.time_sleep)

                        Arm.Arm_serial_servo_write(2, 90, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(3, 90, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(4, 90, self.time_1)
                        time.sleep(self.time_sleep)

                        Arm.Arm_serial_servo_write(2, 180 - 80, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(3, 80, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(4, 80, self.time_1)
                        time.sleep(self.time_sleep)

                        Arm.Arm_serial_servo_write(4, 20, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(6, 150, self.time_1)
                        time.sleep(self.times)
                        time.sleep(self.time_sleep)

                        Arm.Arm_serial_servo_write(4, 90, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(6, 90, self.time_1)
                        time.sleep(self.time_sleep)

                        Arm.Arm_serial_servo_write(4, 20, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(6, 150, self.time_1)
                        time.sleep(self.time_sleep)

                        Arm.Arm_serial_servo_write(4, 90, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(6, 90, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(1, 0, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(5, 0, self.time_1)
                        time.sleep(self.time_sleep)

                        Arm.Arm_serial_servo_write(3, 180, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(4, 0, self.time_1)
                        time.sleep(self.time_sleep)

                        Arm.Arm_serial_servo_write(6, 180, self.time_1)
                        time.sleep(self.time_sleep)

                        Arm.Arm_serial_servo_write(6, 0, self.time_2)
                        time.sleep(self.time_sleep)

                        Arm.Arm_serial_servo_write(6, 90, self.time_2)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(1, 90, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(1, 90, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(5, 90, self.time_1)
                        time.sleep(self.time_sleep)

                        Arm.Arm_serial_servo_write(3, 90, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(4, 90, self.time_1)
                        time.sleep(self.time_sleep)

                        print("well done")
                    elif res == "Ok":
                        print('识别结果' + self.hand.get(res, '未知手势'))
                        myspeak.SetReader(myspeak.Reader_Type["Reader_XiaoYan"])  # 选择播音人许多
                        myspeak.Speech_text("小花看到你比的OK啦", myspeak.EncodingFormat_Type["GB2312"])
                        while myspeak.GetChipStatus() != myspeak.ChipStatus_Type['ChipStatus_Idle']:  # 等待当前语句播报结束
                            time.sleep(0.1)
                        flag = False

                        Arm.Arm_serial_servo_write(2, 180 - 120, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(3, 120, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(4, 60, self.time_1)
                        time.sleep(self.time_sleep)

                        Arm.Arm_serial_servo_write(2, 180 - 120, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(3, 120, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(4, 60, self.time_1)
                        time.sleep(self.time_sleep)
                        Arm.Arm_serial_servo_write(2, 180 - 135, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(3, 135, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(4, 45, self.time_1)
                        time.sleep(self.time_sleep)

                        Arm.Arm_serial_servo_write(2, 90, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(3, 90, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(4, 90, self.time_1)
                        time.sleep(self.time_sleep)

                        Arm.Arm_serial_servo_write(2, 180 - 80, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(3, 80, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(4, 80, self.time_1)
                        time.sleep(self.time_sleep)

                        Arm.Arm_serial_servo_write(2, 180 - 60, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(3, 60, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(4, 60, self.time_1)
                        time.sleep(self.time_sleep)

                        Arm.Arm_serial_servo_write(2, 180 - 45, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(3, 45, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(4, 45, self.time_1)
                        time.sleep(self.time_sleep)

                        Arm.Arm_serial_servo_write(2, 90, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(3, 90, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(4, 90, self.time_1)
                        time.sleep(self.times)
                        time.sleep(self.time_sleep)

                        Arm.Arm_serial_servo_write(3, 180, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(4, 0, self.time_1)
                        time.sleep(self.time_sleep)

                        Arm.Arm_serial_servo_write(6, 180, self.time_1)
                        time.sleep(self.time_sleep)

                        Arm.Arm_serial_servo_write(6, 0, self.time_2)
                        time.sleep(self.time_sleep)
                        Arm.Arm_serial_servo_write(4, 20, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(6, 150, self.time_1)
                        time.sleep(self.times)
                        time.sleep(self.time_sleep)

                        Arm.Arm_serial_servo_write(4, 90, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(6, 90, self.time_1)
                        time.sleep(self.time_sleep)

                        Arm.Arm_serial_servo_write(4, 20, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(6, 150, self.time_1)
                        time.sleep(self.time_sleep)

                        Arm.Arm_serial_servo_write(4, 90, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(6, 90, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(1, 0, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(5, 0, self.time_1)
                        time.sleep(self.time_sleep)

                        Arm.Arm_serial_servo_write(6, 90, self.time_2)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(1, 90, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(1, 90, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(5, 90, self.time_1)
                        time.sleep(self.time_sleep)

                        Arm.Arm_serial_servo_write(3, 90, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(4, 90, self.time_1)
                        time.sleep(self.time_sleep)

                        print("well done")
                    elif res == "Face":
                        print('识别结果' + self.hand.get(res, '未知手势'))
                        myspeak.SetReader(myspeak.Reader_Type["Reader_XiaoYan"])  # 选择播音人许多
                        myspeak.Speech_text("小花看到小主人的脸啦", myspeak.EncodingFormat_Type["GB2312"])
                        while myspeak.GetChipStatus() != myspeak.ChipStatus_Type['ChipStatus_Idle']:  # 等待当前语句播报结束
                            time.sleep(0.1)
                        flag = False

                        Arm.Arm_serial_servo_write(2, 180 - 120, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(3, 120, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(4, 60, self.time_1)
                        time.sleep(self.time_sleep)

                        Arm.Arm_serial_servo_write(2, 180 - 135, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(3, 135, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(4, 45, self.time_1)
                        time.sleep(self.time_sleep)

                        Arm.Arm_serial_servo_write(2, 180 - 120, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(3, 120, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(4, 60, self.time_1)
                        time.sleep(self.time_sleep)

                        Arm.Arm_serial_servo_write(2, 90, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(3, 90, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(4, 90, self.time_1)
                        time.sleep(self.time_sleep)

                        Arm.Arm_serial_servo_write(2, 180 - 80, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(3, 80, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(4, 80, self.time_1)
                        time.sleep(self.time_sleep)

                        Arm.Arm_serial_servo_write(2, 180 - 60, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(3, 60, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(4, 60, self.time_1)
                        time.sleep(self.time_sleep)

                        Arm.Arm_serial_servo_write(2, 180 - 45, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(3, 45, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(4, 45, self.time_1)
                        time.sleep(self.time_sleep)

                        Arm.Arm_serial_servo_write(2, 90, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(3, 90, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(4, 90, self.time_1)
                        time.sleep(self.times)
                        time.sleep(self.time_sleep)

                        Arm.Arm_serial_servo_write(4, 20, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(6, 150, self.time_1)
                        time.sleep(self.times)
                        time.sleep(self.time_sleep)

                        Arm.Arm_serial_servo_write(4, 90, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(6, 90, self.time_1)
                        time.sleep(self.time_sleep)

                        Arm.Arm_serial_servo_write(4, 20, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(6, 150, self.time_1)
                        time.sleep(self.time_sleep)

                        Arm.Arm_serial_servo_write(6, 90, self.time_2)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(1, 90, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(1, 90, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(5, 90, self.time_1)
                        time.sleep(self.time_sleep)

                        Arm.Arm_serial_servo_write(3, 90, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(4, 90, self.time_1)
                        time.sleep(self.time_sleep)
                        Arm.Arm_serial_servo_write(3, 180, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(4, 0, self.time_1)
                        time.sleep(self.time_sleep)

                        Arm.Arm_serial_servo_write(6, 180, self.time_1)
                        time.sleep(self.time_sleep)

                        Arm.Arm_serial_servo_write(6, 0, self.time_2)
                        time.sleep(self.time_sleep)
                        Arm.Arm_serial_servo_write(4, 90, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(6, 90, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(1, 0, self.time_1)
                        time.sleep(self.times)
                        Arm.Arm_serial_servo_write(5, 0, self.time_1)
                        time.sleep(self.time_sleep)
                    else:
                        img = frame

                # Update the image widget with the annotated frame
                # self.image_widget.value = self.bgr8_to_jpeg(img)

        except KeyboardInterrupt:
            cap.release()
            print("程序已关闭！")
            pass


if __name__ == "__main__":
    #     arm_controller = RoboticArmController()
    #     arm_controller.main_game()
    dance = dance()
    dance.start()