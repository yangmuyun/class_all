import cv2
import pyzbar.pyzbar as pyzbar
from Arm_Lib import Arm_Device
import ipywidgets as widgets
import time
import random
from speak import speak


class QRCodeScanner:
    def __init__(self):
        self.arm = Arm_Device()
        time.sleep(0.1)
        self.cap = None

    def arm_move(self, p, s_time=500):
        for i in range(5):
            id = i + 1
            self.arm.Arm_serial_servo_write(id, p[i], s_time)
            time.sleep(0.01)
        time.sleep(s_time / 1000)

    Reader_Type = {
        'Reader_XiaoYan': 3,  # 为 3，设置发音人为小燕(女声, 推荐发音人)
        'Reader_XuJiu': 51,  # 为 51，设置发音人为许久(男声, 推荐发音人)
        'Reader_XuDuo': 52,  # 为 52，设置发音人为许多(男声)
        'Reader_XiaoPing': 53,  # 为 53，设置发音人为小萍(女声
        'Reader_DonaldDuck': 54,  # 为 54，设置发音人为唐老鸭(效果器)
        'Reader_XuXiaoBao': 55  # 为 55，设置发音人为许小宝(女童声)
    }  # 选择发音人 [m?]'

    def decode_display(self, image, image1, flag):
        print('begin code')
        myspeak = speak()

        barcodes = pyzbar.decode(image)
        for barcode in barcodes:
            (x, y, w, h) = barcode.rect
            cv2.rectangle(image1, (x, y), (x + w, y + h), (0, 0, 255), 2)

            barcodeData = barcode.data.decode("utf-8")
            barcodeType = barcode.type

            print("[INFO] Found {} barcode: {}".format(barcodeType, barcodeData))
            if barcodeData == '1':
                print('1 is ok')
                myspeak.SetReader(myspeak.Reader_Type["Reader_XiaoYan"])  # 选择播音人许多
                myspeak.Speech_text("为什么小白兔不嫁给斑马呢,因为兔妈妈说纹身不是好孩子",
                                    myspeak.EncodingFormat_Type["GB2312"])
                while myspeak.GetChipStatus() != myspeak.ChipStatus_Type['ChipStatus_Idle']:  # 等待当前语句播报结束
                    time.sleep(0.1)
                flag = False
            elif barcodeData == '2':
                print('2 is ok')
                myspeak.SetReader(myspeak.Reader_Type["Reader_XiaoYan"])  # 选择播音人许多
                myspeak.Speech_text("为什么蚕宝宝很有钱,因为它会结茧", myspeak.EncodingFormat_Type["GB2312"])
                while myspeak.GetChipStatus() != myspeak.ChipStatus_Type['ChipStatus_Idle']:  # 等待当前语句播报结束
                    time.sleep(0.1)
                flag = False
            elif barcodeData == '3':
                print('3 is ok')
                myspeak.SetReader(myspeak.Reader_Type["Reader_XiaoYan"])  # 选择播音人许多
                myspeak.Speech_text("猴子不喜欢什么线,平行线,因为没有相交", myspeak.EncodingFormat_Type["GB2312"])
                while myspeak.GetChipStatus() != myspeak.ChipStatus_Type['ChipStatus_Idle']:  # 等待当前语句播报结束
                    time.sleep(0.1)
                flag = False
            elif barcodeData == '4':
                print('4 is ok')
                myspeak.SetReader(myspeak.Reader_Type["Reader_XiaoYan"])  # 选择播音人许多
                myspeak.Speech_text("巧克力和西红柿打架，为什么是巧克力赢,因为巧克力棒嘛",
                                    myspeak.EncodingFormat_Type["GB2312"])
                while myspeak.GetChipStatus() != myspeak.ChipStatus_Type['ChipStatus_Idle']:  # 等待当前语句播报结束
                    time.sleep(0.1)
                flag = False
            elif barcodeData == '5':
                print('5 is ok')
                myspeak.SetReader(myspeak.Reader_Type["Reader_XiaoYan"])  # 选择播音人许多
                myspeak.Speech_text("鲨鱼吃了绿豆会怎么样,变成了绿豆沙", myspeak.EncodingFormat_Type["GB2312"])
                while myspeak.GetChipStatus() != myspeak.ChipStatus_Type['ChipStatus_Idle']:  # 等待当前语句播报结束
                    time.sleep(0.1)
                flag = False
            else:
                print('error')
        return image1, flag

    def bgr8_to_jpeg(self, image):
        image_rgb = cv2.cvtColor(image, cv2.COLOR_BGR2RGB)
        _, jpeg = cv2.imencode('.jpg', image_rgb)
        return jpeg.tobytes()

    def main(self):
        myspeak = speak()
        flag = True
        p = [90, 135, 20, 25, 90, 30]
        self.arm_move(p, 500)
        time.sleep(1)
        #         cap = cv2.VideoCapture(1)
        cap = self.cap
        if not cap.isOpened():
            print("Error: Could not open camera.")
            return -1  # 返回-1表示摄像头打开失败

        b_time = 10
        a_time = 1
        self.arm.Arm_Buzzer_On(b_time)
        time.sleep(10)
        self.arm.Arm_Buzzer_On(a_time)
        print("begin photo")
        #         myspeak.SetReader(myspeak.Reader_Type["Reader_XiaoYan"])  # 选择播音人许多
        #         myspeak.Speech_text("小花要开始说花花的笑话了，请小主人不要吃东西哦", myspeak.EncodingFormat_Type["GB2312"])
        #         while myspeak.GetChipStatus() != myspeak.ChipStatus_Type['ChipStatus_Idle']:  # 等待当前语句播报结束
        #             time.sleep(0.1)
        while flag:
            # 读取当前帧

            ret, img = cap.read()
            # 打开摄像头
            cap.set(3, 640)
            cap.set(4, 480)
            cap.set(5, 30)  # Frame rate
            cap.set(cv2.CAP_PROP_FOURCC, cv2.VideoWriter.fourcc('M', 'J', 'P', 'G'))
            cap.set(cv2.CAP_PROP_BRIGHTNESS, 40)  # Brightness
            cap.set(cv2.CAP_PROP_CONTRAST, 50)  # Contrast
            cap.set(cv2.CAP_PROP_EXPOSURE, 156)  # Exposure

            # Read the initial frame
            ret, frame = cap.read()

            # Display widget for camera feed
            #             image_widget = widgets.Image(format='jpeg', width=600, height=500)
            #             display(image_widget)
            #             image_widget.value = self.bgr8_to_jpeg(frame)
            b_time = 10
            self.arm.Arm_Buzzer_On(b_time)
            print('1')
            if not ret:
                print("Error: Could not read frame.")
            # 转为灰度图像
            print(f"Frame type: {type(frame)}, Frame shape: {frame.shape}")
            gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
            # 准备开始识别
            c_time = 1
            time.sleep(1)
            self.arm.Arm_Buzzer_On(c_time)
            time.sleep(0.1)
            self.arm.Arm_Buzzer_On(c_time)
            # 进入识别
            im, flag = self.decode_display(gray, img, flag)
            cv2.imshow("image", im)

            key = cv2.waitKey(5)

            if key == 27:
                break

    def capture_qr_codes(self):
        try:
            self.main()
        except KeyboardInterrupt:
            cap.release()
            print("\nProgram closed!")
        finally:
            time.sleep(1)


if __name__ == "__main__":
    #     game = robot_DiceGame()
    #     game.start_robot_game()
    try:
        qr_scanner = QRCodeScanner()
        qr_scanner.capture_qr_codes()
    except KeyboardInterrupt:
        #         cap.release()
        print("\nProgram closed!")
    finally:
        time.sleep(1)