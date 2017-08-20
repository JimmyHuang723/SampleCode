from celery import Celery
import time

app = Celery('tasks', backend='rpc://', broker='amqp://jimmy723:jimmy723@localhost:5672')

@app.task
def add(x, y):
    time.sleep(5)    
    return x + y






